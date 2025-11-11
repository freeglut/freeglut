#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <stdbool.h>

#define MAX_LINE 16384
#define MAX_HEADERS 500

typedef struct {
    char *text;
    size_t size;
    size_t capacity;
} StringBuilder;

typedef struct {
    int level;        // 1, 2, or 3
    char *text;       // Header text
    char *anchor;     // Anchor ID for linking
} Header;

typedef struct {
    Header headers[MAX_HEADERS];
    int count;
} HeaderList;

// Configuration
typedef struct {
    const char *skip_h1_header;  // Header text to skip (NULL if none)
    bool generate_toc;
    const char *top_level_header;  // Optional top-level header text
    const char *input_file;
    const char *output_file;
} Config;

// StringBuilder functions
StringBuilder* sb_create(void) {
    StringBuilder *sb = malloc(sizeof(StringBuilder));
    sb->capacity = 8192;
    sb->size = 0;
    sb->text = malloc(sb->capacity);
    sb->text[0] = '\0';
    return sb;
}

void sb_append(StringBuilder *sb, const char *str) {
    size_t len = strlen(str);
    while (sb->size + len + 1 > sb->capacity) {
        sb->capacity *= 2;
        sb->text = realloc(sb->text, sb->capacity);
    }
    strcpy(sb->text + sb->size, str);
    sb->size += len;
}

void sb_append_char(StringBuilder *sb, char c) {
    if (sb->size + 2 > sb->capacity) {
        sb->capacity *= 2;
        sb->text = realloc(sb->text, sb->capacity);
    }
    sb->text[sb->size++] = c;
    sb->text[sb->size] = '\0';
}

void sb_free(StringBuilder *sb) {
    free(sb->text);
    free(sb);
}

// Header list functions
void headers_init(HeaderList *list) {
    list->count = 0;
}

void headers_add(HeaderList *list, int level, const char *text, const char *anchor) {
    if (list->count >= MAX_HEADERS) return;

    Header *h = &list->headers[list->count++];
    h->level = level;
    h->text = strdup(text);
    h->anchor = strdup(anchor);
}

void headers_free(HeaderList *list) {
    for (int i = 0; i < list->count; i++) {
        free(list->headers[i].text);
        free(list->headers[i].anchor);
    }
}

// Generate anchor ID from header text
char* generate_anchor(const char *text) {
    static char anchor[256];
    int j = 0;

    for (int i = 0; text[i] && j < 255; i++) {
        char c = text[i];
        if (isalnum(c)) {
            anchor[j++] = tolower(c);
        } else if (isspace(c) && j > 0 && anchor[j-1] != '-') {
            anchor[j++] = '-';
        }
    }

    // Remove trailing dash
    if (j > 0 && anchor[j-1] == '-') j--;
    anchor[j] = '\0';

    return anchor;
}

// Utility functions
char *trim(char *str) {
    char *end;
    while(isspace((unsigned char)*str)) str++;
    if(*str == 0) return str;
    end = str + strlen(str) - 1;
    while(end > str && isspace((unsigned char)*end)) end--;
    end[1] = '\0';
    return str;
}

// Extract text content from HTML tags (removes all tags)
void extract_text_content(const char *html, StringBuilder *output) {
    const char *p = html;
    while (*p) {
        if (*p == '<') {
            const char *end = strchr(p, '>');
            if (end) {
                p = end + 1;
                continue;
            }
        }

        // Handle HTML entities
        if (*p == '&') {
            if (strncmp(p, "&lt;", 4) == 0) {
                sb_append_char(output, '<');
                p += 4;
            } else if (strncmp(p, "&gt;", 4) == 0) {
                sb_append_char(output, '>');
                p += 4;
            } else if (strncmp(p, "&amp;", 5) == 0) {
                sb_append_char(output, '&');
                p += 5;
            } else if (strncmp(p, "&quot;", 6) == 0) {
                sb_append_char(output, '"');
                p += 6;
            } else if (strncmp(p, "&nbsp;", 6) == 0) {
                sb_append_char(output, ' ');
                p += 6;
            } else {
                sb_append_char(output, *p++);
            }
        } else {
            sb_append_char(output, *p++);
        }
    }
}

// Convert inline HTML to markdown
void convert_inline_html(const char *html, StringBuilder *output) {
    const char *p = html;

    while (*p) {
        if (*p == '<') {
            const char *tag_end = strchr(p, '>');
            if (!tag_end) {
                sb_append_char(output, *p++);
                continue;
            }

            char tag[256];
            size_t tag_len = tag_end - p + 1;
            if (tag_len >= sizeof(tag)) tag_len = sizeof(tag) - 1;
            strncpy(tag, p, tag_len);
            tag[tag_len] = '\0';

            // Code tags
            if (strncmp(tag, "<tt>", 4) == 0 || strncmp(tag, "<code>", 6) == 0) {
                sb_append_char(output, '`');
                p = tag_end + 1;
                continue;
            }
            if (strncmp(tag, "</tt>", 5) == 0 || strncmp(tag, "</code>", 7) == 0) {
                sb_append_char(output, '`');
                p = tag_end + 1;
                continue;
            }

            // Bold tags
            if (strncmp(tag, "<b>", 3) == 0 || strncmp(tag, "<strong>", 8) == 0) {
                sb_append(output, "**");
                p = tag_end + 1;
                continue;
            }
            if (strncmp(tag, "</b>", 4) == 0 || strncmp(tag, "</strong>", 9) == 0) {
                sb_append(output, "**");
                p = tag_end + 1;
                continue;
            }

            // Italic tags
            if (strncmp(tag, "<i>", 3) == 0 || strncmp(tag, "<em>", 4) == 0) {
                sb_append_char(output, '*');
                p = tag_end + 1;
                continue;
            }
            if (strncmp(tag, "</i>", 4) == 0 || strncmp(tag, "</em>", 5) == 0) {
                sb_append_char(output, '*');
                p = tag_end + 1;
                continue;
            }

            // Underline tags
            if (strncmp(tag, "<u>", 3) == 0) {
                sb_append(output, "__");
                p = tag_end + 1;
                continue;
            }
            if (strncmp(tag, "</u>", 4) == 0) {
                sb_append(output, "__");
                p = tag_end + 1;
                continue;
            }

            // Links (check for <a followed by whitespace)
            if (tag[0] == '<' && tag[1] == 'a' && isspace((unsigned char)tag[2])) {
                const char *href_start = strstr(tag, "href=\"");
                if (href_start) {
                    href_start += 6;
                    const char *href_end = strchr(href_start, '"');
                    if (href_end) {
                        char href[512];
                        size_t href_len = href_end - href_start;
                        if (href_len >= sizeof(href)) href_len = sizeof(href) - 1;
                        strncpy(href, href_start, href_len);
                        href[href_len] = '\0';

                        p = tag_end + 1;
                        const char *link_end = strcasestr(p, "</a>");
                        if (link_end) {
                            char link_text[512];
                            size_t text_len = link_end - p;
                            if (text_len >= sizeof(link_text)) text_len = sizeof(link_text) - 1;
                            strncpy(link_text, p, text_len);
                            link_text[text_len] = '\0';

                            sb_append_char(output, '[');
                            StringBuilder *temp = sb_create();
                            convert_inline_html(link_text, temp);
                            sb_append(output, temp->text);
                            sb_free(temp);
                            sb_append(output, "](");
                            sb_append(output, href);
                            sb_append_char(output, ')');

                            p = link_end + 4;
                            continue;
                        }
                    }
                }
            }

            // Line breaks
            if (strncmp(tag, "<br/>", 5) == 0 || strncmp(tag, "<br />", 6) == 0 ||
                strncmp(tag, "<br>", 4) == 0) {
                sb_append(output, "  \n");
                p = tag_end + 1;
                continue;
            }

            // Skip other tags
            p = tag_end + 1;
            continue;
        }

        // Handle HTML entities
        if (*p == '&') {
            if (strncmp(p, "&lt;", 4) == 0) {
                sb_append_char(output, '<');
                p += 4;
            } else if (strncmp(p, "&gt;", 4) == 0) {
                sb_append_char(output, '>');
                p += 4;
            } else if (strncmp(p, "&amp;", 5) == 0) {
                sb_append_char(output, '&');
                p += 5;
            } else if (strncmp(p, "&quot;", 6) == 0) {
                sb_append_char(output, '"');
                p += 6;
            } else if (strncmp(p, "&nbsp;", 6) == 0) {
                sb_append_char(output, ' ');
                p += 6;
            } else if (strncmp(p, "&larr;", 6) == 0) {
                sb_append(output, "←");
                p += 6;
            } else if (strncmp(p, "&rarr;", 6) == 0) {
                sb_append(output, "→");
                p += 6;
            } else {
                sb_append_char(output, *p++);
            }
        } else {
            sb_append_char(output, *p++);
        }
    }
}

// Convert HTML to Markdown (for API documentation)
void html_to_markdown(const char *html, StringBuilder *output, HeaderList *headers, Config *config) {
    const char *p = html;
    int list_depth = 0;
    int ordered_list[10] = {0};
    bool in_paragraph = false;
    bool in_list_item = false;
    bool skip_to_body = true;
    bool skipping_contents = false;

    while (*p) {
        // Skip PHP blocks
        if (strncmp(p, "<?", 2) == 0) {
            while (*p && strncmp(p, "?>", 2) != 0) p++;
            if (*p) p += 2;
            continue;
        }

        // Skip comments
        if (strncmp(p, "<!--", 4) == 0) {
            while (*p && strncmp(p, "-->", 3) != 0) p++;
            if (*p) p += 3;
            continue;
        }

        // Skip everything until we hit the main content
        if (skip_to_body && strncmp(p, "<h1>", 4) != 0 && strncmp(p, "<h1 ", 4) != 0) {
            if (*p == '<') {
                const char *end = strchr(p, '>');
                if (end) p = end + 1;
                else p++;
            } else {
                p++;
            }
            continue;
        }

        // Handle HTML tags (only block-level tags)
        if (*p == '<') {
            const char *tag_end = strchr(p, '>');
            if (!tag_end) {
                p++;
                continue;
            }

            char tag[512];
            size_t tag_len = tag_end - p + 1;
            if (tag_len >= sizeof(tag)) tag_len = sizeof(tag) - 1;
            strncpy(tag, p, tag_len);
            tag[tag_len] = '\0';

            // Check if this is a block-level tag we handle
            // If it's an inline tag (like <b>, <i>, <tt>, <a>), fall through to text processing
            bool is_block_tag = (
                strncmp(tag, "<h1", 3) == 0 ||
                strncmp(tag, "<h2", 3) == 0 ||
                strncmp(tag, "<h3", 3) == 0 ||
                strncmp(tag, "<p>", 3) == 0 ||
                strncmp(tag, "<p ", 3) == 0 ||
                strncmp(tag, "</p>", 4) == 0 ||
                strncmp(tag, "<ul", 3) == 0 ||
                strncmp(tag, "</ul>", 5) == 0 ||
                strncmp(tag, "<ol", 3) == 0 ||
                strncmp(tag, "</ol>", 5) == 0 ||
                strncmp(tag, "<li", 3) == 0 ||
                strncmp(tag, "</li>", 5) == 0 ||
                strncmp(tag, "<pre", 4) == 0 ||
                strncmp(tag, "</pre>", 6) == 0
            );

            if (!is_block_tag) {
                // Not a block-level tag, let text processing handle it
                // Don't advance p here - let the text processing section handle this tag
                goto process_text;
            }

            // H1 headings
            if (strncmp(tag, "<h1", 3) == 0) {
                skip_to_body = false;

                p = tag_end + 1;
                const char *h1_end = strcasestr(p, "</h1>");
                if (h1_end) {
                    char heading[512];
                    size_t len = h1_end - p;
                    if (len >= sizeof(heading)) len = sizeof(heading) - 1;
                    strncpy(heading, p, len);
                    heading[len] = '\0';

                    StringBuilder *temp = sb_create();
                    extract_text_content(heading, temp);
                    char *heading_text = trim(temp->text);

                    // Add to header list (always, even if we skip output)
                    char *anchor = generate_anchor(heading_text);
                    headers_add(headers, 1, heading_text, anchor);

                    // Check if this is the header to skip
                    if (config->skip_h1_header &&
                        strcasestr(heading_text, config->skip_h1_header)) {
                        skipping_contents = true;
                        sb_free(temp);
                        p = h1_end + 5;
                        continue;
                    }

                    // If we were skipping and hit a new h1, stop skipping
                    if (skipping_contents) {
                        skipping_contents = false;
                    }

                    // Output the heading (h1 -> ##)
                    if (in_paragraph) {
                        sb_append(output, "\n\n");
                        in_paragraph = false;
                    }
                    if (output->size > 0 && output->text[output->size - 1] != '\n') {
                        sb_append(output, "\n\n");
                    }

                    sb_append(output, "## ");
                    sb_append(output, heading_text);
                    sb_append(output, "\n\n");

                    sb_free(temp);
                    p = h1_end + 5;
                }
                continue;
            }

            // If we're skipping contents, skip everything until next h1
            if (skipping_contents) {
                p = tag_end + 1;
                continue;
            }

            // H2 headings
            if (strncmp(tag, "<h2", 3) == 0) {
                p = tag_end + 1;
                const char *h2_end = strcasestr(p, "</h2>");
                if (h2_end) {
                    char heading[512];
                    size_t len = h2_end - p;
                    if (len >= sizeof(heading)) len = sizeof(heading) - 1;
                    strncpy(heading, p, len);
                    heading[len] = '\0';

                    StringBuilder *temp = sb_create();
                    extract_text_content(heading, temp);
                    char *heading_text = trim(temp->text);

                    // Add to header list
                    char *anchor = generate_anchor(heading_text);
                    headers_add(headers, 2, heading_text, anchor);

                    if (in_paragraph) {
                        sb_append(output, "\n\n");
                        in_paragraph = false;
                    }
                    if (output->size > 0 && output->text[output->size - 1] != '\n') {
                        sb_append(output, "\n\n");
                    }

                    sb_append(output, "### ");
                    sb_append(output, heading_text);
                    sb_append(output, "\n\n");

                    sb_free(temp);
                    p = h2_end + 5;
                }
                continue;
            }

            // H3 headings
            if (strncmp(tag, "<h3", 3) == 0) {
                p = tag_end + 1;
                const char *h3_end = strcasestr(p, "</h3>");
                if (h3_end) {
                    char heading[512];
                    size_t len = h3_end - p;
                    if (len >= sizeof(heading)) len = sizeof(heading) - 1;
                    strncpy(heading, p, len);
                    heading[len] = '\0';

                    StringBuilder *temp = sb_create();
                    extract_text_content(heading, temp);
                    char *heading_text = trim(temp->text);

                    // Add to header list
                    char *anchor = generate_anchor(heading_text);
                    headers_add(headers, 3, heading_text, anchor);

                    if (in_paragraph) {
                        sb_append(output, "\n\n");
                        in_paragraph = false;
                    }
                    if (output->size > 0 && output->text[output->size - 1] != '\n') {
                        sb_append(output, "\n\n");
                    }

                    sb_append(output, "#### ");
                    sb_append(output, heading_text);
                    sb_append(output, "\n\n");

                    sb_free(temp);
                    p = h3_end + 5;
                }
                continue;
            }

            // Pre-formatted code blocks
            if (strncmp(tag, "<pre", 4) == 0) {
                p = tag_end + 1;
                const char *pre_end = strcasestr(p, "</pre>");
                if (pre_end) {
                    if (in_paragraph) {
                        sb_append(output, "\n\n");
                        in_paragraph = false;
                    }
                    if (output->size > 0 && output->text[output->size - 1] != '\n') {
                        sb_append(output, "\n\n");
                    }

                    // Extract code content
                    char code[16384];
                    size_t len = pre_end - p;
                    if (len >= sizeof(code)) len = sizeof(code) - 1;
                    strncpy(code, p, len);
                    code[len] = '\0';

                    // Output as markdown code fence
                    sb_append(output, "```c\n");

                    // Process the code content - convert HTML entities back to characters
                    char *c = code;
                    while (*c) {
                        if (*c == '&') {
                            if (strncmp(c, "&lt;", 4) == 0) {
                                sb_append_char(output, '<');
                                c += 4;
                            } else if (strncmp(c, "&gt;", 4) == 0) {
                                sb_append_char(output, '>');
                                c += 4;
                            } else if (strncmp(c, "&amp;", 5) == 0) {
                                sb_append_char(output, '&');
                                c += 5;
                            } else if (strncmp(c, "&nbsp;", 6) == 0) {
                                sb_append_char(output, ' ');
                                c += 6;
                            } else {
                                sb_append_char(output, *c++);
                            }
                        } else {
                            sb_append_char(output, *c++);
                        }
                    }

                    sb_append(output, "```\n\n");
                    p = pre_end + 6;
                }
                continue;
            }

            // Paragraphs
            if (strncmp(tag, "<p>", 3) == 0 || strncmp(tag, "<p ", 3) == 0) {
                // Check for multiline <tt> pattern: <p><tt>\n
                const char *check_pos = tag_end + 1;
                if (strncmp(check_pos, "<tt>", 4) == 0 &&
                    (check_pos[4] == '\n' || check_pos[4] == '\r')) {

                    // This is a multiline code block
                    p = check_pos + 4;
                    while (*p == '\n' || *p == '\r') p++;

                    // Find the closing </tt>
                    const char *tt_end = strcasestr(p, "</tt>");
                    if (tt_end) {
                        // Extract content between <tt> and </tt>
                        char content[8192];
                        size_t content_len = tt_end - p;
                        if (content_len >= sizeof(content)) content_len = sizeof(content) - 1;
                        strncpy(content, p, content_len);
                        content[content_len] = '\0';

                        // Convert the content: join lines, only breaking at semicolons
                        StringBuilder *temp = sb_create();
                        const char *cp = content;
                        while (*cp) {
                            if (strncmp(cp, "<br>", 4) == 0 ||
                                strncmp(cp, "<br/>", 5) == 0 ||
                                strncmp(cp, "<br />", 6) == 0) {
                                // Skip <br> tags - we'll add newlines only at semicolons
                                cp += (*cp == '<' && *(cp+3) == '>') ? 4 : (*(cp+4) == '>') ? 5 : 6;
                                // Add a space if we're joining lines (unless there's already whitespace)
                                if (temp->size > 0 && temp->text[temp->size - 1] != ' ' &&
                                    temp->text[temp->size - 1] != '\n') {
                                    sb_append_char(temp, ' ');
                                }
                            } else if (strncmp(cp, "&nbsp;", 6) == 0) {
                                sb_append_char(temp, ' ');
                                cp += 6;
                            } else if (strncmp(cp, "&lt;", 4) == 0) {
                                sb_append_char(temp, '<');
                                cp += 4;
                            } else if (strncmp(cp, "&gt;", 4) == 0) {
                                sb_append_char(temp, '>');
                                cp += 4;
                            } else if (strncmp(cp, "&amp;", 5) == 0) {
                                sb_append_char(temp, '&');
                                cp += 5;
                            } else if (*cp == '\n' || *cp == '\r') {
                                // Skip literal newlines, add space instead if needed
                                if (temp->size > 0 && temp->text[temp->size - 1] != ' ' &&
                                    temp->text[temp->size - 1] != '\n') {
                                    sb_append_char(temp, ' ');
                                }
                                cp++;
                            } else {
                                sb_append_char(temp, *cp);
                                // Add newline after semicolons
                                if (*cp == ';') {
                                    sb_append_char(temp, '\n');
                                }
                                cp++;
                            }
                        }

                        // Add code block markers
                        if (output->size > 0 && output->text[output->size - 1] != '\n') {
                            sb_append(output, "\n\n");
                        }
                        sb_append(output, "```c\n");
                        sb_append(output, temp->text);
                        if (temp->size > 0 && temp->text[temp->size - 1] != '\n') {
                            sb_append_char(output, '\n');
                        }
                        sb_append(output, "```\n\n");

                        sb_free(temp);

                        // Skip past </tt> and </p>
                        p = tt_end + 5;  // past </tt>
                        while (*p && isspace((unsigned char)*p) && *p != '\n' && *p != '\r') p++;
                        if (strncmp(p, "</p>", 4) == 0) {
                            p += 4;
                        }
                        while (*p && (*p == '\n' || *p == '\r')) p++;

                        continue;
                    }
                }

                // Regular paragraph
                if (in_paragraph || in_list_item) {
                    // Already in paragraph or list
                } else {
                    if (output->size > 0 && output->text[output->size - 1] != '\n') {
                        sb_append(output, "\n\n");
                    }
                }
                in_paragraph = true;
                p = tag_end + 1;
                continue;
            }
            if (strncmp(tag, "</p>", 4) == 0) {
                // Trim trailing whitespace from output before adding newlines
                while (output->size > 0 && isspace((unsigned char)output->text[output->size - 1])) {
                    output->size--;
                    output->text[output->size] = '\0';
                }

                // Always add double newline to ensure paragraph break
                sb_append(output, "\n\n");
                in_paragraph = false;
                p = tag_end + 1;

                // Skip all newline characters until we find a non-newline character
                while (*p && (*p == '\n' || *p == '\r')) {
                    p++;
                }
                continue;
            }

            // Ordered lists
            if (strncmp(tag, "<ol>", 4) == 0 || strncmp(tag, "<ol ", 4) == 0) {
                if (!in_list_item && output->size > 0) {
                    // Count existing trailing newlines
                    int trailing_newlines = 0;
                    size_t pos = output->size - 1;
                    while (pos > 0 && output->text[pos] == '\n') {
                        trailing_newlines++;
                        pos--;
                    }

                    // We want exactly 2 newlines (one blank line) for top-level lists
                    if (list_depth == 0) {  // About to become depth 1
                        if (trailing_newlines < 2) {
                            for (int i = trailing_newlines; i < 2; i++) {
                                sb_append(output, "\n");
                            }
                        }
                    } else if (trailing_newlines == 0) {
                        sb_append(output, "\n");
                    }
                }
                list_depth++;
                ordered_list[list_depth] = 1;
                p = tag_end + 1;
                continue;
            }
            if (strncmp(tag, "</ol>", 5) == 0) {
                list_depth--;
                if (!in_list_item) sb_append(output, "\n");
                p = tag_end + 1;
                continue;
            }

            // Unordered lists
            if (strncmp(tag, "<ul>", 4) == 0 || strncmp(tag, "<ul ", 4) == 0) {
                if (!in_list_item && output->size > 0) {
                    // Count existing trailing newlines
                    int trailing_newlines = 0;
                    size_t pos = output->size - 1;
                    while (pos > 0 && output->text[pos] == '\n') {
                        trailing_newlines++;
                        pos--;
                    }

                    // We want exactly 2 newlines (one blank line) for top-level lists
                    if (list_depth == 0) {  // About to become depth 1
                        if (trailing_newlines < 2) {
                            for (int i = trailing_newlines; i < 2; i++) {
                                sb_append(output, "\n");
                            }
                        }
                    } else if (trailing_newlines == 0) {
                        sb_append(output, "\n");
                    }
                }
                list_depth++;
                ordered_list[list_depth] = 0;
                p = tag_end + 1;
                continue;
            }
            if (strncmp(tag, "</ul>", 5) == 0) {
                list_depth--;
                if (!in_list_item) sb_append(output, "\n");
                p = tag_end + 1;
                continue;
            }

            // List items
            if (strncmp(tag, "<li>", 4) == 0 || strncmp(tag, "<li ", 4) == 0) {
                // Count trailing newlines before trimming
                int newline_count = 0;
                size_t pos = output->size;
                while (pos > 0 && output->text[pos - 1] == '\n') {
                    newline_count++;
                    pos--;
                }

                // Remove all trailing whitespace
                while (output->size > 0 && isspace((unsigned char)output->text[output->size - 1])) {
                    output->size--;
                    output->text[output->size] = '\0';
                }

                // Add back up to 2 newlines (blank line before first item, single line between items)
                int newlines_to_add = (newline_count < 2) ? newline_count : 2;
                for (int i = 0; i < newlines_to_add; i++) {
                    sb_append(output, "\n");
                }

                // Ensure we have at least one newline before the list item
                if (newlines_to_add == 0 && output->size > 0) {
                    sb_append(output, "\n");
                }

                // Add indentation for nested lists (depth > 1)
                // Top-level lists (depth 1) have no indentation
                for (int i = 1; i < list_depth; i++) {
                    sb_append(output, "  ");  // 2 spaces per level
                }

                if (ordered_list[list_depth] == 0) {
                    sb_append(output, "- ");
                } else {
                    char num[16];
                    snprintf(num, sizeof(num), "%d. ", ordered_list[list_depth]++);
                    sb_append(output, num);
                }

                in_list_item = true;
                p = tag_end + 1;

                // Skip leading whitespace after <li> tag
                while (*p && isspace((unsigned char)*p)) {
                    p++;
                }

                continue;
            }
            if (strncmp(tag, "</li>", 5) == 0) {
                // Trim trailing whitespace before closing list item
                while (output->size > 0 && isspace((unsigned char)output->text[output->size - 1])) {
                    output->size--;
                    output->text[output->size] = '\0';
                }

                sb_append(output, "\n");
                in_list_item = false;
                p = tag_end + 1;
                continue;
            }

            // Should not reach here - all block tags should be handled above
            p = tag_end + 1;
            continue;
        }

process_text:
        // Regular text
        // Process text if: not whitespace, OR in a list item, OR in a paragraph but not in a list
        if (!skipping_contents && (!isspace((unsigned char)*p) || (in_list_item || (in_paragraph && list_depth == 0)))) {
            // Find the next block-level tag (not inline tags like <tt>, <b>, <a>)
            const char *next_block_tag = p;
            while (*next_block_tag) {
                const char *tag_start = strchr(next_block_tag, '<');
                if (!tag_start) {
                    // No more tags, process rest of content
                    StringBuilder *temp = sb_create();
                    convert_inline_html(p, temp);
                    sb_append(output, temp->text);
                    sb_free(temp);
                    p += strlen(p);
                    break;
                }

                // Check if this is a block-level tag
                if (strncmp(tag_start, "</p>", 4) == 0 ||
                    strncmp(tag_start, "<p>", 3) == 0 ||
                    strncmp(tag_start, "<p ", 3) == 0 ||
                    strncmp(tag_start, "<h1", 3) == 0 ||
                    strncmp(tag_start, "<h2", 3) == 0 ||
                    strncmp(tag_start, "<h3", 3) == 0 ||
                    strncmp(tag_start, "</h1>", 5) == 0 ||
                    strncmp(tag_start, "</h2>", 5) == 0 ||
                    strncmp(tag_start, "</h3>", 5) == 0 ||
                    strncmp(tag_start, "<div", 4) == 0 ||
                    strncmp(tag_start, "</div>", 6) == 0 ||
                    strncmp(tag_start, "<ul", 3) == 0 ||
                    strncmp(tag_start, "</ul>", 5) == 0 ||
                    strncmp(tag_start, "<ol", 3) == 0 ||
                    strncmp(tag_start, "</ol>", 5) == 0 ||
                    strncmp(tag_start, "<li", 3) == 0 ||
                    strncmp(tag_start, "</li>", 5) == 0 ||
                    strncmp(tag_start, "<pre", 4) == 0 ||
                    strncmp(tag_start, "</pre>", 6) == 0) {
                    // Found a block-level tag, process text up to here
                    char text[8192];
                    size_t len = tag_start - p;
                    if (len >= sizeof(text)) len = sizeof(text) - 1;
                    strncpy(text, p, len);
                    text[len] = '\0';

                    StringBuilder *temp = sb_create();
                    convert_inline_html(text, temp);
                    sb_append(output, temp->text);
                    sb_free(temp);

                    p = tag_start;
                    break;
                }

                // It's an inline tag, skip past it and continue
                const char *tag_end = strchr(tag_start, '>');
                if (tag_end) {
                    next_block_tag = tag_end + 1;
                } else {
                    next_block_tag++;
                }
            }
        } else {
            p++;
        }
    }
}

// Generate table of contents from headers
void generate_toc(HeaderList *headers, StringBuilder *output) {
    sb_append(output, "## Table of Contents\n\n");

    for (int i = 0; i < headers->count; i++) {
        Header *h = &headers->headers[i];

        // Add indentation based on level
        // Level 1 (h1) -> no indent
        // Level 2 (h2) -> one tab
        // Level 3 (h3) -> two tabs
        for (int j = 1; j < h->level; j++) {
            sb_append(output, "\t");
        }

        // All entries use bullet points
        sb_append(output, "- ");

        // Add link
        sb_append(output, "[");
        sb_append(output, h->text);
        sb_append(output, "](#");
        sb_append(output, h->anchor);
        sb_append(output, ")\n");
    }

    sb_append(output, "\n---\n\n");
}

void print_usage(const char *prog_name) {
    printf("Usage: %s [OPTIONS] <input_file> <output_file>\n\n", prog_name);
    printf("Convert HTML to Markdown.\n\n");
    printf("OPTIONS:\n");
    printf("  --skip-h1-header <text>      Skip H1 header containing this text (and all content until next H1)\n");
    printf("  --generate-toc               Generate table of contents from headers\n");
    printf("  --top-level-header <text>    Add a top-level H1 header with the specified text\n");
    printf("  --help                       Show this help message\n\n");
    printf("ARGUMENTS:\n");
    printf("  input_file                   Input HTML file (required)\n");
    printf("  output_file                  Output Markdown file (required)\n\n");
    printf("EXAMPLES:\n");
    printf("  %s input.html output.md\n", prog_name);
    printf("  %s --skip-h1-header \"Contents\" --generate-toc input.html output.md\n", prog_name);
    printf("  %s --top-level-header \"freeglut API\" --generate-toc input.html output.md\n", prog_name);
    printf("  %s --skip-h1-header \"Contents\" --generate-toc --top-level-header \"freeglut API\" api.php api.md\n",
            prog_name);
}

int main(int argc, char *argv[]) {
    Config config = {
        .skip_h1_header = NULL,
        .generate_toc = false,
        .top_level_header = NULL,
        .input_file = NULL,
        .output_file = NULL
    };

    // Parse command line arguments
    int file_arg_idx = 1;
    for (int i = 1; i < argc; i++) {
        if (strcmp(argv[i], "--skip-h1-header") == 0) {
            if (i + 1 < argc) {
                config.skip_h1_header = argv[i + 1];
                i++;  // Skip the value
                file_arg_idx += 2;
            } else {
                fprintf(stderr, "Error: --skip-h1-header requires a value\n");
                print_usage(argv[0]);
                return 1;
            }
        } else if (strcmp(argv[i], "--generate-toc") == 0) {
            config.generate_toc = true;
            file_arg_idx++;
        } else if (strcmp(argv[i], "--top-level-header") == 0) {
            if (i + 1 < argc) {
                config.top_level_header = argv[i + 1];
                i++;  // Skip the value
                file_arg_idx += 2;
            } else {
                fprintf(stderr, "Error: --top-level-header requires a value\n");
                print_usage(argv[0]);
                return 1;
            }
        } else if (strcmp(argv[i], "--help") == 0 || strcmp(argv[i], "-h") == 0) {
            print_usage(argv[0]);
            return 0;
        } else {
            break;
        }
    }

    // Get input/output files
    if (file_arg_idx < argc) {
        config.input_file = argv[file_arg_idx];
    }
    if (file_arg_idx + 1 < argc) {
        config.output_file = argv[file_arg_idx + 1];
    }

    // Validate required arguments
    if (!config.input_file || !config.output_file) {
        fprintf(stderr, "Error: Both input_file and output_file are required\n\n");
        print_usage(argv[0]);
        return 1;
    }

    printf("Converting HTML to Markdown...\n");
    printf("Input: %s\n", config.input_file);
    printf("Output: %s\n", config.output_file);
    printf("Top Level Header: %s\n", config.top_level_header ? config.top_level_header : "none");
    printf("Skip H1 Header: %s\n", config.skip_h1_header ? config.skip_h1_header : "none");
    printf("Generate TOC: %s\n\n", config.generate_toc ? "yes" : "no");

    // Read input file
    FILE *fp = fopen(config.input_file, "r");
    if (!fp) {
        fprintf(stderr, "Error: Cannot open file %s\n", config.input_file);
        return 1;
    }

    StringBuilder *content = sb_create();
    char line[MAX_LINE];
    while (fgets(line, sizeof(line), fp)) {
        sb_append(content, line);
    }
    fclose(fp);

    // Convert to markdown
    HeaderList headers;
    headers_init(&headers);

    StringBuilder *markdown = sb_create();
    html_to_markdown(content->text, markdown, &headers, &config);

    // Generate final output
    StringBuilder *final_output = sb_create();

    // Add top-level header if specified
    if (config.top_level_header) {
        sb_append(final_output, "# ");
        sb_append(final_output, config.top_level_header);
        sb_append(final_output, "\n\n");
    }

    if (config.generate_toc) {
        generate_toc(&headers, final_output);
    }

    sb_append(final_output, markdown->text);

    // Write output
    FILE *out = fopen(config.output_file, "w");
    if (!out) {
        fprintf(stderr, "Error: Cannot create output file %s\n", config.output_file);
        sb_free(content);
        sb_free(markdown);
        sb_free(final_output);
        headers_free(&headers);
        return 1;
    }

    fprintf(out, "%s", final_output->text);
    fclose(out);

    printf("Conversion complete!\n");
    printf("Generated: %s (%zu bytes)\n", config.output_file, final_output->size);
    printf("Headers found: %d\n", headers.count);

    sb_free(content);
    sb_free(markdown);
    sb_free(final_output);
    headers_free(&headers);

    return 0;
}
