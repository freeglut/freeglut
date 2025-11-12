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

// Extract anchor name from markdown link header
// e.g., "## 2. Introduction" -> "Introduction"
// or "### 3.1 Design Philosophy" -> "Design Philosophy"
char* extract_anchor_name(const char *header_text) {
    static char anchor[256];
    const char *p = header_text;

    // Skip leading numbers and dots (e.g., "2. " or "3.1 ")
    while (*p && (isdigit(*p) || *p == '.' || isspace(*p))) {
        if (isdigit(*p)) {
            while (isdigit(*p)) p++;
        } else {
            p++;
        }
    }

    strncpy(anchor, p, sizeof(anchor) - 1);
    anchor[sizeof(anchor) - 1] = '\0';
    return anchor;
}

// Generate anchor ID for Table of Contents (simplified version)
char* generate_toc_anchor(const char *text) {
    static char anchor[256];
    static char temp[256];
    const char *p = text;

    // Skip section numbers
    while (*p && (isdigit(*p) || *p == '.' || isspace(*p))) {
        p++;
    }

    // Copy to temp buffer
    strncpy(temp, p, sizeof(temp) - 1);
    temp[sizeof(temp) - 1] = '\0';

    // Special case handling for specific sections to match original anchors
    struct {
        const char *title;
        const char *anchor_text;
    } special_cases[] = {
        {"Window-Specific Callback Registration Functions", "Window Callback"},
        {"Global Callback Registration Functions", "Global Callback"},
        {"Geometric Object Rendering Functions", "Geometric Object"},
        {"Font Rendering Functions", "Font Rendering"},
        {"State Setting and Retrieval Functions", "State Setting"}
    };

    // Check if this matches any special case
    for (int k = 0; k < sizeof(special_cases) / sizeof(special_cases[0]); k++) {
        if (strcmp(temp, special_cases[k].title) == 0) {
            strncpy(temp, special_cases[k].anchor_text, sizeof(temp) - 1);
            temp[sizeof(temp) - 1] = '\0';
            break;
        }
    }

    // If not a special case, strip common suffixes
    if (strcmp(temp, p) == 0) {  // No special case matched
        const char *suffixes[] = {
            " Functions"
        };

        for (int k = 0; k < sizeof(suffixes) / sizeof(suffixes[0]); k++) {
            size_t len = strlen(temp);
            size_t suffix_len = strlen(suffixes[k]);
            if (len > suffix_len && strcmp(temp + len - suffix_len, suffixes[k]) == 0) {
                temp[len - suffix_len] = '\0';
            }
        }
    }

    // For TOC, we use CamelCase names like "Introduction", "EventProcessing", etc.
    int j = 0;
    bool new_word = true;

    for (int i = 0; temp[i] && j < 255; i++) {
        char c = temp[i];
        if (isalnum(c)) {
            if (new_word) {
                anchor[j++] = c;
                new_word = false;
            } else {
                anchor[j++] = c;
            }
        } else if (isspace(c) || c == '-') {
            new_word = true;
        }
    }

    anchor[j] = '\0';
    return anchor;
}

// Escape special HTML characters
void append_escaped(StringBuilder *sb, const char *text) {
    for (const char *p = text; *p; p++) {
        switch (*p) {
            case '<':
                sb_append(sb, "&lt;");
                break;
            case '>':
                sb_append(sb, "&gt;");
                break;
            case '&':
                sb_append(sb, "&amp;");
                break;
            case '"':
                sb_append(sb, "&quot;");
                break;
            default:
                sb_append_char(sb, *p);
        }
    }
}

// Convert inline markdown to HTML
void convert_inline_md(const char *md, StringBuilder *output) {
    const char *p = md;

    while (*p) {
        // Code (backticks)
        if (*p == '`') {
            p++;
            sb_append(output, "<tt>");
            while (*p && *p != '`') {
                if (*p == '<') sb_append(output, "&lt;");
                else if (*p == '>') sb_append(output, "&gt;");
                else if (*p == '&') sb_append(output, "&amp;");
                else sb_append_char(output, *p);
                p++;
            }
            sb_append(output, "</tt>");
            if (*p == '`') p++;
            continue;
        }

        // Bold (**text**)
        if (*p == '*' && *(p+1) == '*') {
            p += 2;
            sb_append(output, "<b>");
            while (*p && !(*p == '*' && *(p+1) == '*')) {
                sb_append_char(output, *p++);
            }
            sb_append(output, "</b>");
            if (*p == '*') p += 2;
            continue;
        }

        // Underline (__text__)
        if (*p == '_' && *(p+1) == '_') {
            p += 2;
            sb_append(output, "<u>");
            while (*p && !(*p == '_' && *(p+1) == '_')) {
                sb_append_char(output, *p++);
            }
            sb_append(output, "</u>");
            if (*p == '_') p += 2;
            continue;
        }

        // Italic (*text*)
        if (*p == '*') {
            p++;
            sb_append(output, "<i>");
            while (*p && *p != '*') {
                sb_append_char(output, *p++);
            }
            sb_append(output, "</i>");
            if (*p == '*') p++;
            continue;
        }

        // Links [text](url)
        if (*p == '[') {
            // Check for double bracket [[text](url)] - just output the text part
            if (*(p+1) == '[') {
                p += 2;  // Skip [[
                char link_text[512] = {0};
                int i = 0;
                // Read until ]
                while (*p && *p != ']' && i < sizeof(link_text) - 1) {
                    link_text[i++] = *p++;
                }
                if (*p == ']') p++;
                if (*p == '(') {
                    // Skip the URL part
                    while (*p && *p != ')') p++;
                    if (*p == ')') p++;
                }
                if (*p == ']') p++;  // Skip final ]

                // Just output the text in brackets
                sb_append(output, "[");
                sb_append(output, link_text);
                sb_append(output, "]");
                continue;
            }

            p++;
            char link_text[512] = {0};
            int i = 0;
            while (*p && *p != ']' && i < sizeof(link_text) - 1) {
                link_text[i++] = *p++;
            }
            if (*p == ']') p++;
            if (*p == '(') {
                p++;
                char href[512] = {0};
                i = 0;
                while (*p && *p != ')' && i < sizeof(href) - 1) {
                    href[i++] = *p++;
                }
                if (*p == ')') p++;

                sb_append(output, "<a href=\"");
                sb_append(output, href);
                sb_append(output, "\">");
                convert_inline_md(link_text, output);
                sb_append(output, "</a>");
                continue;
            }
        }

        // Unicode arrows
        if ((unsigned char)*p == 0xE2 && (unsigned char)*(p+1) == 0x86) {
            if ((unsigned char)*(p+2) == 0x90) {  // ←
                sb_append(output, "&larr;");
                p += 3;
                continue;
            } else if ((unsigned char)*(p+2) == 0x92) {  // →
                sb_append(output, "&rarr;");
                p += 3;
                continue;
            }
        }

        // Regular character
        sb_append_char(output, *p++);
    }
}

// Convert TOC list to HTML and return pointer to where TOC ends
const char* convert_toc_to_html(const char *md, StringBuilder *output) {
    char line[MAX_LINE];
    bool in_toc = false;
    int ol_depth = 0;

    // Read line by line
    const char *p = md;
    while (*p) {
        // Extract line
        int i = 0;
        const char *line_start = p;
        while (*p && *p != '\n' && i < MAX_LINE - 1) {
            line[i++] = *p++;
        }
        line[i] = '\0';
        if (*p == '\n') p++;

        // Check if this is the TOC header
        if (strncmp(line, "## Table of Contents", 20) == 0) {
            in_toc = true;
            sb_append(output, "<h1>1. <a name=\"Contents\"></a>Contents</h1>\n\n");
            continue;
        }

        // Check for end of TOC (horizontal rule or next section)
        if (in_toc && (strncmp(line, "---", 3) == 0 || strncmp(line, "## ", 3) == 0)) {
            // Close all open lists
            while (ol_depth > 0) {
                sb_append(output, "\t</ol>\n");
                ol_depth--;
            }
            if (strncmp(line, "---", 3) == 0) {
                return p;  // Return pointer past the ---  line
            } else {
                // Hit next section, return pointer to start of that line
                return line_start;
            }
        }

        if (!in_toc) continue;

        // Skip empty lines in TOC
        char *trimmed = trim(line);
        if (strlen(trimmed) == 0) continue;

        // Count indentation (tabs)
        int indent = 0;
        for (const char *t = line; *t == '\t'; t++) indent++;

        // Determine list depth (0-indexed)
        int depth = indent + 1;  // Convert to 1-indexed

        // Skip items with depth > 2 (only show 2 levels in TOC)
        if (depth > 2) continue;

        // Adjust list depth
        if (ol_depth < depth) {
            // Opening nested list - don't close previous </li> yet
            while (ol_depth < depth) {
                sb_append(output, "\t<ol>\n");
                ol_depth++;
            }
        } else if (ol_depth > depth) {
            // Closing nested lists - close </li> first, then close </ol>
            sb_append(output, "</li>\n");
            while (ol_depth > depth) {
                sb_append(output, "\t</ol>\n");
                ol_depth--;
            }
        } else {
            // Same depth - close previous </li> before starting new one
            sb_append(output, "</li>\n");
        }

        // Parse list item: "- [text](#anchor)"
        const char *list_start = trimmed;
        if (*list_start == '-') {
            list_start++;
            while (*list_start == ' ') list_start++;

            // Parse markdown link
            if (*list_start == '[') {
                list_start++;
                char link_text[512] = {0};
                int j = 0;
                while (*list_start && *list_start != ']' && j < sizeof(link_text) - 1) {
                    link_text[j++] = *list_start++;
                }
                if (*list_start == ']') list_start++;
                if (*list_start == '(') {
                    list_start++;
                    char href[512] = {0};
                    j = 0;
                    while (*list_start && *list_start != ')' && j < sizeof(href) - 1) {
                        href[j++] = *list_start++;
                    }

                    sb_append(output, "\t\t<li>");

                    // Check if this is a top-level item that needs an anchor
                    if (depth == 1 && href[0] == '#') {
                        // Generate anchor from link_text (CamelCase format)
                        char *anchor = generate_toc_anchor(link_text);
                        sb_append(output, "<a href=\"#");
                        sb_append(output, anchor);  // Use generated CamelCase anchor instead of markdown href
                        sb_append(output, "\">");

                        // Strip section number from top-level items (e.g., "2. Introduction" -> "Introduction")
                        const char *text_without_number = link_text;
                        while (*text_without_number && (isdigit(*text_without_number) || *text_without_number == '.')) {
                            text_without_number++;
                        }
                        while (*text_without_number == ' ') {
                            text_without_number++;
                        }
                        sb_append(output, text_without_number);
                        sb_append(output, "</a>");
                    } else {
                        // For sub-items, strip the section number (e.g., "7.4 glutSwapInterval" -> "glutSwapInterval")
                        const char *text_without_number = link_text;
                        // Skip over number prefix like "7.4 " or "12.18 "
                        while (*text_without_number && (isdigit(*text_without_number) || *text_without_number == '.')) {
                            text_without_number++;
                        }
                        // Skip space after number
                        while (*text_without_number == ' ') {
                            text_without_number++;
                        }
                        sb_append(output, text_without_number);
                    }

                    // Don't close </li> here - it will be closed before the next item or at the end
                }
            }
        }
    }

    // Close final list item and remaining lists
    if (ol_depth > 0) {
        sb_append(output, "</li>\n");  // Close the last list item
    }
    while (ol_depth > 0) {
        sb_append(output, "\t</ol>\n");
        ol_depth--;
    }

    return p;  // Return end of string
}

// Convert markdown to HTML
void markdown_to_html(const char *md, StringBuilder *output) {
    const char *p = md;
    char line[MAX_LINE];
    bool in_paragraph = false;
    bool skip_blank_lines = false;
    int line_num = 0;

    while (*p) {
process_line_start:
        // Extract line
        int i = 0;
        while (*p && *p != '\n' && i < MAX_LINE - 1) {
            line[i++] = *p++;
        }
        line[i] = '\0';
        if (*p == '\n') p++;
        line_num++;

        char *trimmed = trim(line);

        // Skip the top-level "# freeglut API" header
        if (strncmp(trimmed, "# freeglut API", 14) == 0) {
            continue;
        }

        // Table of Contents
        if (strncmp(trimmed, "## Table of Contents", 20) == 0) {
            if (in_paragraph) {
                sb_append(output, "</p>\n\n");
                in_paragraph = false;
            }
            // Convert TOC and update pointer to skip past it
            p = convert_toc_to_html(p - strlen(line) - 1, output);
            continue;
        }

        // Headers (must have space after # marks, e.g., "## Title" not "#ifdef")
        if (trimmed[0] == '#') {
            // Count # symbols
            int level = 0;
            const char *check = trimmed;
            while (*check == '#') {
                level++;
                check++;
            }

            // Must have a space after the # symbols to be a real markdown header
            // Otherwise it's code (like #ifdef, #include, #define, etc.)
            bool is_header = (*check == ' ' || *check == '\0');

            if (is_header && in_paragraph) {
                sb_append(output, "</p>\n\n");
                in_paragraph = false;
            }

            if (is_header) {
                char *header_text = trimmed + level;
                while (*header_text == ' ') header_text++;

                if (level == 2) {  // ## -> <h1>
                    // Extract section number and section name
                    // e.g., "14. Font Rendering Functions" -> "14. " and "Font Rendering Functions"
                    const char *p_num = header_text;
                    const char *section_start = header_text;

                    // Skip section number (digits and dots)
                    while (*p_num && (isdigit(*p_num) || *p_num == '.')) {
                        p_num++;
                    }
                    // Skip spaces after number
                    while (*p_num == ' ') {
                        section_start = p_num + 1;
                        p_num++;
                    }

                    // Generate anchor from section name (without number)
                    char *anchor = generate_toc_anchor(section_start);

                    // Output: <h1>14. <a name="FontRendering"></a>Font Rendering Functions</h1>
                    sb_append(output, "<h1>");
                    // Output section number if present
                    if (section_start > header_text) {
                        char num_buf[64];
                        size_t num_len = section_start - header_text;
                        if (num_len >= sizeof(num_buf)) num_len = sizeof(num_buf) - 1;
                        strncpy(num_buf, header_text, num_len);
                        num_buf[num_len] = '\0';
                        sb_append(output, num_buf);
                    }
                    sb_append(output, "<a name=\"");
                    sb_append(output, anchor);
                    sb_append(output, "\"></a>");
                    sb_append(output, section_start);
                    sb_append(output, "</h1>\n\n");
                } else if (level == 3) {  // ### -> <h2>
                    // Generate anchor from full header text (e.g., "3.2 Conventions" -> "32-conventions")
                    char anchor_buf[256];
                    const char *p_anchor = header_text;
                    int j = 0;

                    for (int i = 0; p_anchor[i] && j < 255; i++) {
                        char c = p_anchor[i];
                        if (isalnum(c)) {
                            anchor_buf[j++] = tolower(c);
                        } else if (c == '.' && i > 0 && isdigit(p_anchor[i-1]) && isdigit(p_anchor[i+1])) {
                            // Skip periods between digits (e.g., "3.2" -> "32")
                            continue;
                        } else if (isspace(c) && j > 0 && anchor_buf[j-1] != '-') {
                            anchor_buf[j++] = '-';
                        }
                    }
                    // Remove trailing dash
                    if (j > 0 && anchor_buf[j-1] == '-') j--;
                    anchor_buf[j] = '\0';

                    // Extract section number and text (e.g., "3.2 Conventions" -> "3.2 " and "Conventions")
                    const char *p_num = header_text;
                    const char *section_text = header_text;

                    // Skip section number (digits and dots)
                    while (*p_num && (isdigit(*p_num) || *p_num == '.')) {
                        p_num++;
                    }
                    // Skip spaces after number
                    while (*p_num == ' ') {
                        section_text = p_num + 1;
                        p_num++;
                    }

                    // Output: <h2>3.2 <a name="32-conventions"></a>Conventions</h2>
                    sb_append(output, "<h2>");
                    // Output section number if present
                    if (section_text > header_text) {
                        char num_buf[64];
                        size_t num_len = section_text - header_text;
                        if (num_len >= sizeof(num_buf)) num_len = sizeof(num_buf) - 1;
                        strncpy(num_buf, header_text, num_len);
                        num_buf[num_len] = '\0';
                        sb_append(output, num_buf);
                    }
                    sb_append(output, "<a name=\"");
                    sb_append(output, anchor_buf);
                    sb_append(output, "\"></a>");
                    sb_append(output, section_text);
                    sb_append(output, "</h2>\n\n");
                } else if (level == 4) {  // #### -> <h3>
                    // Generate anchor from full header text
                    char anchor_buf[256];
                    const char *p_anchor = header_text;
                    int j = 0;

                    for (int i = 0; p_anchor[i] && j < 255; i++) {
                        char c = p_anchor[i];
                        if (isalnum(c)) {
                            anchor_buf[j++] = tolower(c);
                        } else if (c == '.' && i > 0 && isdigit(p_anchor[i-1]) && isdigit(p_anchor[i+1])) {
                            // Skip periods between digits (e.g., "3.2.1" -> "321")
                            continue;
                        } else if (isspace(c) && j > 0 && anchor_buf[j-1] != '-') {
                            anchor_buf[j++] = '-';
                        }
                    }
                    // Remove trailing dash
                    if (j > 0 && anchor_buf[j-1] == '-') j--;
                    anchor_buf[j] = '\0';

                    // Extract section number and text
                    const char *p_num = header_text;
                    const char *section_text = header_text;

                    // Skip section number (digits and dots)
                    while (*p_num && (isdigit(*p_num) || *p_num == '.')) {
                        p_num++;
                    }
                    // Skip spaces after number
                    while (*p_num == ' ') {
                        section_text = p_num + 1;
                        p_num++;
                    }

                    // Output: <h3>3.2.1 <a name="321-window-placement"></a>Window placement</h3>
                    sb_append(output, "<h3>");
                    // Output section number if present
                    if (section_text > header_text) {
                        char num_buf[64];
                        size_t num_len = section_text - header_text;
                        if (num_len >= sizeof(num_buf)) num_len = sizeof(num_buf) - 1;
                        strncpy(num_buf, header_text, num_len);
                        num_buf[num_len] = '\0';
                        sb_append(output, num_buf);
                    }
                    sb_append(output, "<a name=\"");
                    sb_append(output, anchor_buf);
                    sb_append(output, "\"></a>");
                    sb_append(output, section_text);
                    sb_append(output, "</h3>\n\n");
                }
                continue;
            }
            // If not a header (it's code like #ifdef), handle as bare code block
            if (!is_header) {
                // This looks like a bare code block (preprocessor directives, etc.)
                if (in_paragraph) {
                    sb_append(output, "</p>\n\n");
                    in_paragraph = false;
                }

                sb_append(output, "<pre>\n");

                // Collect all consecutive code-like lines
                while (*p) {
                    // Output current line
                    // Escape HTML in code
                    for (const char *c = trimmed; *c; c++) {
                        if (*c == '<') sb_append(output, "&lt;");
                        else if (*c == '>') sb_append(output, "&gt;");
                        else if (*c == '&') sb_append(output, "&amp;");
                        else sb_append_char(output, *c);
                    }
                    sb_append(output, "\n");

                    // Get next line
                    i = 0;
                    while (*p && *p != '\n' && i < MAX_LINE - 1) {
                        line[i++] = *p++;
                    }
                    line[i] = '\0';
                    if (*p == '\n') p++;
                    trimmed = trim(line);

                    // Check if still in code block (starts with # but not a header, or is empty)
                    if (strlen(trimmed) == 0) {
                        // Empty line might end the code block
                        // Peek ahead to see if there's more code
                        const char *peek = p;
                        char peek_line[MAX_LINE];
                        int j = 0;
                        while (*peek && *peek != '\n' && j < MAX_LINE - 1) {
                            peek_line[j++] = *peek++;
                        }
                        peek_line[j] = '\0';
                        char *peek_trimmed = trim(peek_line);

                        // If next line is also code or empty, this is just spacing within the block
                        if (strlen(peek_trimmed) == 0 ||
                            (peek_trimmed[0] == '#' && peek_trimmed[1] != ' ' && peek_trimmed[1] != '#')) {
                            // Continue code block
                            continue;
                        } else {
                            // End of code block
                            break;
                        }
                    }

                    // Not a code line if it doesn't start with # (and isn't empty)
                    if (trimmed[0] != '#') break;

                    // Check if it's a header (# followed by space)
                    int hash_count = 0;
                    const char *ch = trimmed;
                    while (*ch == '#') { hash_count++; ch++; }
                    if (*ch == ' ' || *ch == '\0') break;  // It's a header, end code block
                }

                sb_append(output, "</pre>\n\n");

                // Process the current line that ended the code block
                if (strlen(trimmed) > 0) {
                    goto process_line_start;
                }
                continue;
            }
        }

        // Code blocks (```c or ``` )
        if (strncmp(trimmed, "```", 3) == 0) {
            if (in_paragraph) {
                sb_append(output, "</p>\n\n");
                in_paragraph = false;
            }

            // Read code block content
            StringBuilder *code = sb_create();
            while (*p) {
                i = 0;
                while (*p && *p != '\n' && i < MAX_LINE - 1) {
                    line[i++] = *p++;
                }
                line[i] = '\0';
                if (*p == '\n') p++;

                if (strncmp(line, "```", 3) == 0) break;

                // Escape HTML entities and convert spaces to &nbsp;
                for (int j = 0; line[j]; j++) {
                    if (line[j] == '<') {
                        sb_append(code, "&lt;");
                    } else if (line[j] == '>') {
                        sb_append(code, "&gt;");
                    } else if (line[j] == '&') {
                        sb_append(code, "&amp;");
                    } else if (line[j] == ' ') {
                        sb_append(code, "&nbsp;");
                    } else {
                        sb_append_char(code, line[j]);
                    }
                }
                sb_append(code, "<br>\n");
            }

            sb_append(output, "<p><tt>\n");
            sb_append(output, code->text);
            sb_append(output, "</tt></p>\n\n");
            sb_free(code);
            continue;
        }

        // Horizontal rule
        if (strncmp(trimmed, "---", 3) == 0) {
            if (in_paragraph) {
                sb_append(output, "</p>\n\n");
                in_paragraph = false;
            }
            continue;
        }

        // Indented code block (4 spaces or 1 tab) - check BEFORE empty line check
        if ((line[0] == ' ' && line[1] == ' ' && line[2] == ' ' && line[3] == ' ') ||
            line[0] == '\t') {
            if (in_paragraph) {
                sb_append(output, "</p>\n\n");
                in_paragraph = false;
            }

            sb_append(output, "<pre>\n");

            // Collect all indented lines
            while (*p) {
                // Remove the indentation and output the line
                const char *code_line = line;
                if (code_line[0] == '\t') {
                    code_line++;
                } else if (code_line[0] == ' ' && code_line[1] == ' ' &&
                           code_line[2] == ' ' && code_line[3] == ' ') {
                    code_line += 4;
                }

                // Escape HTML in code
                for (const char *c = code_line; *c && *c != '\n'; c++) {
                    if (*c == '<') sb_append(output, "&lt;");
                    else if (*c == '>') sb_append(output, "&gt;");
                    else if (*c == '&') sb_append(output, "&amp;");
                    else sb_append_char(output, *c);
                }
                sb_append(output, "\n");

                // Get next line
                i = 0;
                while (*p && *p != '\n' && i < MAX_LINE - 1) {
                    line[i++] = *p++;
                }
                line[i] = '\0';
                if (*p == '\n') p++;

                // Check if still indented or blank
                if (line[0] == '\0' || strlen(trim(line)) == 0) {
                    // Blank line - check if there's more code after it
                    const char *peek = p;
                    char peek_line[MAX_LINE];
                    int j = 0;
                    while (*peek && *peek != '\n' && j < MAX_LINE - 1) {
                        peek_line[j++] = *peek++;
                    }
                    peek_line[j] = '\0';

                    // If next line is also indented, continue the code block
                    if ((peek_line[0] == ' ' && peek_line[1] == ' ' &&
                         peek_line[2] == ' ' && peek_line[3] == ' ') ||
                        peek_line[0] == '\t') {
                        sb_append(output, "\n");
                        continue;
                    } else {
                        // End of code block
                        break;
                    }
                }

                // Not indented - end of code block
                if (!(line[0] == ' ' && line[1] == ' ' && line[2] == ' ' && line[3] == ' ') &&
                    line[0] != '\t') {
                    trimmed = trim(line);
                    break;
                }
            }

            sb_append(output, "</pre>\n\n");

            // Process the current line that ended the code block
            if (strlen(trimmed) > 0) {
                goto process_line_start;
            }
            continue;
        }

        // Empty line (check after indented code block check)
        if (strlen(trimmed) == 0) {
            if (in_paragraph) {
                sb_append(output, "</p>\n\n");
                in_paragraph = false;
            }
            continue;
        }

        // Unordered list
        if (trimmed[0] == '-' && trimmed[1] == ' ') {
            if (in_paragraph) {
                sb_append(output, "</p>\n\n");
                in_paragraph = false;
            }

            // Track list depth (1-indexed, top level = 1)
            int list_depth = 1;
            int prev_indent = 0;  // Indentation of previous item (in spaces)

            sb_append(output, "<ul>\n");

            // Process list items
            bool first_item = true;
            while (*p || first_item) {
                if (!first_item) {
                    // Get next line
                    i = 0;
                    while (*p && *p != '\n' && i < MAX_LINE - 1) {
                        line[i++] = *p++;
                    }
                    line[i] = '\0';
                    if (*p == '\n') p++;
                    trimmed = trim(line);
                }

                // Check if we've hit a blank line
                if (strlen(trimmed) == 0) {
                    // Peek ahead to see if there are indented list items coming
                    const char *peek = p;
                    const char *peek_start = p;
                    char peek_line[MAX_LINE];
                    bool found_indented_item = false;

                    // Skip additional blank lines and look for list items
                    while (*peek) {
                        int j = 0;
                        const char *line_start = peek;
                        while (*peek && *peek != '\n' && j < MAX_LINE - 1) {
                            peek_line[j++] = *peek++;
                        }
                        peek_line[j] = '\0';
                        if (*peek == '\n') peek++;

                        char *peek_trimmed = trim(peek_line);
                        if (strlen(peek_trimmed) == 0) {
                            continue;  // Skip blank lines
                        }

                        // Check if it's a list item
                        if (peek_trimmed[0] == '-' && peek_trimmed[1] == ' ') {
                            // Check if it's indented
                            int peek_indent = 0;
                            for (const char *c = peek_line; *c == ' '; c++) {
                                peek_indent++;
                            }
                            if (peek_indent > 0) {
                                found_indented_item = true;
                                // Skip past blank lines to this indented item
                                p = line_start;
                            }
                        }
                        break;  // Found a non-blank line
                    }

                    // If we found indented items ahead, continue processing
                    if (!found_indented_item) {
                        break;
                    }
                    // Otherwise, we've positioned p at the indented item, continue loop
                    continue;
                }

                // Check if this is still a list item
                if (!(trimmed[0] == '-' && trimmed[1] == ' ')) {
                    // Not a list item marker, end of list
                    break;
                }

                // Count leading spaces in original line to determine nesting level
                int indent = 0;
                for (const char *c = line; *c == ' '; c++) {
                    indent++;
                }

                // Calculate desired depth based on indentation (2 spaces per level)
                int desired_depth = (indent / 2) + 1;

                // Adjust list depth by opening or closing <ul> tags
                if (desired_depth > list_depth) {
                    // Going deeper - don't close previous </li>, open nested list inside it
                    while (list_depth < desired_depth) {
                        sb_append(output, "\n<ul>\n");
                        list_depth++;
                    }
                } else if (desired_depth < list_depth) {
                    // Going shallower - close current item and nested lists
                    if (!first_item) {
                        sb_append(output, "</li>\n");
                    }
                    while (list_depth > desired_depth) {
                        sb_append(output, "</ul>\n");
                        list_depth--;
                    }
                } else {
                    // Same depth - close previous item if not first
                    if (!first_item) {
                        sb_append(output, "</li>\n");
                    }
                }

                first_item = false;
                prev_indent = indent;

                // Output list item start
                sb_append(output, "\t<li>");

                // Accumulate the text content (for inline MD conversion)
                StringBuilder *item = sb_create();
                char *item_text = trimmed + 2;  // Skip "- "
                sb_append(item, item_text);

                // Continue reading continuation lines, paragraphs, and nested lists
                int blank_count = 0;
                bool just_output_nested = false;  // Track if we just output a nested list
                bool is_complex_item = false;  // Track if this item has nested content or multiple paragraphs
                while (*p) {
                    // Peek at next line
                    i = 0;
                    const char *peek_start = p;
                    while (*p && *p != '\n' && i < MAX_LINE - 1) {
                        line[i++] = *p++;
                    }
                    line[i] = '\0';
                    if (*p == '\n') p++;

                    char *next_trimmed = trim(line);

                    // If blank line, count it but keep checking
                    if (strlen(next_trimmed) == 0) {
                        blank_count++;
                        // Stop if too many blank lines without content
                        if (blank_count > 3) {
                            p = peek_start;
                            break;
                        }
                        // Peek ahead to see what comes after blank lines
                        // If we find text ending with colon (intro to nested list), keep going
                        // Otherwise for simple items, stop at blank line
                        if (!is_complex_item && blank_count > 0) {
                            // Look ahead for text followed by dashes (nested list pattern)
                            const char *peek2 = p;
                            char peek_line2[MAX_LINE];
                            bool found_colon_text = false;

                            // Skip additional blank lines
                            while (*peek2) {
                                int j = 0;
                                while (*peek2 && *peek2 != '\n' && j < MAX_LINE - 1) {
                                    peek_line2[j++] = *peek2++;
                                }
                                peek_line2[j] = '\0';
                                if (*peek2 == '\n') peek2++;

                                char *peek_trim2 = trim(peek_line2);
                                if (strlen(peek_trim2) > 0) {
                                    // Found non-blank line
                                    size_t len = strlen(peek_trim2);
                                    if (len > 0 && peek_trim2[len-1] == ':') {
                                        found_colon_text = true;
                                    }
                                    break;
                                }
                            }

                            // If no colon-ending text ahead, stop at this blank line
                            if (!found_colon_text) {
                                p = peek_start;
                                break;
                            }
                        }
                        // Otherwise, don't break yet - peek ahead to see if there's nested content
                        continue;
                    }

                    // Stop if starts with # (header)
                    if (next_trimmed[0] == '#') {
                        p = peek_start;
                        break;
                    }

                    // If we hit a dash, check if it's nested content or a new item
                    if (next_trimmed[0] == '-' && next_trimmed[1] == ' ') {
                        // Check if this should be a nested list or a new top-level item
                        // It's nested if:
                        // 1. We have accumulated text
                        // 2. There were blank lines before the dash
                        // 3. The last line of accumulated text ends with a colon (introducing the list)
                        bool is_nested = false;
                        if (strlen(item->text) > 0 && blank_count > 0) {
                            // Check if the last non-blank line ends with a colon
                            char *last_line = strrchr(item->text, '\n');
                            if (last_line) {
                                last_line++;  // Skip the newline
                            } else {
                                last_line = item->text;  // No newline, whole text is last line
                            }
                            // Trim and check for colon
                            char *trimmed_last = trim(last_line);
                            size_t len = strlen(trimmed_last);
                            if (len > 0 && trimmed_last[len-1] == ':') {
                                is_nested = true;
                            }
                        }

                        if (is_nested) {
                            // This is nested list - output what we have so far, then the nested list
                            convert_inline_md(item->text, output);
                            sb_append(output, "\n");

                            // Clear item buffer to collect any text between here and nested list
                            sb_free(item);
                            item = sb_create();

                            sb_append(output, "<ul>\n");
                            while (*p) {
                                // Add this nested list item
                                char *nested_text = next_trimmed + 2;
                                sb_append(output, "\t<li>");
                                convert_inline_md(nested_text, output);
                                sb_append(output, "</li>\n");

                                // Check next line
                                i = 0;
                                peek_start = p;
                                while (*p && *p != '\n' && i < MAX_LINE - 1) {
                                    line[i++] = *p++;
                                }
                                line[i] = '\0';
                                if (*p == '\n') p++;
                                next_trimmed = trim(line);

                                // Stop nested list if not a dash item
                                if (!(next_trimmed[0] == '-' && next_trimmed[1] == ' ')) {
                                    p = peek_start;
                                    break;
                                }
                            }
                            sb_append(output, "</ul>\n<br>");
                            blank_count = 0;
                            just_output_nested = true;
                            is_complex_item = true;  // Mark as complex since it has nested list
                            continue;
                        } else if (blank_count == 0) {
                            // No blank lines, so this is a new top-level list item
                            p = peek_start;
                            break;
                        }
                        // If we get here, it's a new top-level list item after blank lines
                        p = peek_start;
                        break;
                    }

                    // Add line breaks for blank lines we skipped
                    if (blank_count > 0 && !just_output_nested) {
                        sb_append(item, "<br><br>\n");
                        is_complex_item = true;  // Mark as complex since it has blank lines

                        // Only reset blank_count if the next line won't be a nested list
                        // Peek ahead to see if there's a dash coming
                        const char *peek2 = p;
                        char peek_line[MAX_LINE];
                        int peek_i = 0;
                        while (*peek2 && *peek2 != '\n' && peek_i < MAX_LINE - 1) {
                            peek_line[peek_i++] = *peek2++;
                        }
                        peek_line[peek_i] = '\0';
                        char *peek_trimmed = trim(peek_line);

                        // If next line is not a dash, reset blank_count
                        if (!(peek_trimmed[0] == '-' && peek_trimmed[1] == ' ')) {
                            blank_count = 0;
                        }
                    } else {
                        sb_append(item, "\n");
                    }

                    // Reset the flag after processing continuation text
                    if (just_output_nested) {
                        just_output_nested = false;
                        blank_count = 0;
                    }

                    // This is a continuation line or paragraph
                    sb_append(item, next_trimmed);
                }

                // Output any remaining text content
                convert_inline_md(item->text, output);
                // Don't close </li> here - nesting logic handles it
                sb_free(item);

                // Only add <br> if this was a complex item (nested list or multiple paragraphs)
                if (is_complex_item) {
                    // Peek ahead to see if there's another list item coming
                    i = 0;
                    const char *peek_ahead = p;
                    char peek_line[MAX_LINE];
                    while (*peek_ahead && *peek_ahead != '\n' && i < MAX_LINE - 1) {
                        peek_line[i++] = *peek_ahead++;
                    }
                    peek_line[i] = '\0';
                    char *peek_trimmed = trim(peek_line);

                    // If next non-blank line is a list item, add <br>
                    if (peek_trimmed[0] == '-' && peek_trimmed[1] == ' ') {
                        sb_append(output, "<br>\n");
                    }
                }
            }

            // Close the final list item and any remaining nested lists
            if (!first_item) {  // Only if we processed at least one item
                sb_append(output, "</li>\n");
            }
            while (list_depth > 1) {
                sb_append(output, "</ul>\n</li>\n");  // Close nested list and parent item
                list_depth--;
            }
            sb_append(output, "</ul>\n\n");

            // The current line in 'trimmed' that ended the list needs to be processed
            // If it's blank, skip it and read next line
            if (strlen(trimmed) == 0) {
                continue;
            }

            // Check what type of line ended the list and handle it appropriately
            // Headers
            if (trimmed[0] == '#') {
                // Count # symbols
                int level = 0;
                const char *check = trimmed;
                while (*check == '#') {
                    level++;
                    check++;
                }
                // Must have a space after the # symbols
                if (*check == ' ' || *check == '\0') {
                    char *header_text = trimmed + level;
                    while (*header_text == ' ') header_text++;

                    if (level == 2) {  // ## -> <h1>
                        // Extract section number and section name
                        const char *p_num = header_text;
                        const char *section_start = header_text;

                        // Skip section number (digits and dots)
                        while (*p_num && (isdigit(*p_num) || *p_num == '.')) {
                            p_num++;
                        }
                        // Skip spaces after number
                        while (*p_num == ' ') {
                            section_start = p_num + 1;
                            p_num++;
                        }

                        // Generate anchor from section name (without number)
                        char *anchor = generate_toc_anchor(section_start);

                        // Output: <h1>14. <a name="FontRendering"></a>Font Rendering Functions</h1>
                        sb_append(output, "<h1>");
                        // Output section number if present
                        if (section_start > header_text) {
                            char num_buf[64];
                            size_t num_len = section_start - header_text;
                            if (num_len >= sizeof(num_buf)) num_len = sizeof(num_buf) - 1;
                            strncpy(num_buf, header_text, num_len);
                            num_buf[num_len] = '\0';
                            sb_append(output, num_buf);
                        }
                        sb_append(output, "<a name=\"");
                        sb_append(output, anchor);
                        sb_append(output, "\"></a>");
                        sb_append(output, section_start);
                        sb_append(output, "</h1>\n\n");
                    } else if (level == 3) {  // ### -> <h2>
                        // Generate anchor from full header text
                        char anchor_buf[256];
                        const char *p_anchor = header_text;
                        int j = 0;

                        for (int i = 0; p_anchor[i] && j < 255; i++) {
                            char c = p_anchor[i];
                            if (isalnum(c)) {
                                anchor_buf[j++] = tolower(c);
                            } else if ((isspace(c) || c == '.') && j > 0 && anchor_buf[j-1] != '-') {
                                anchor_buf[j++] = '-';
                            }
                        }
                        // Remove trailing dash
                        if (j > 0 && anchor_buf[j-1] == '-') j--;
                        anchor_buf[j] = '\0';

                        // Extract section number and text
                        const char *p_num = header_text;
                        const char *section_text = header_text;

                        // Skip section number (digits and dots)
                        while (*p_num && (isdigit(*p_num) || *p_num == '.')) {
                            p_num++;
                        }
                        // Skip spaces after number
                        while (*p_num == ' ') {
                            section_text = p_num + 1;
                            p_num++;
                        }

                        sb_append(output, "<h2>");
                        // Output section number if present
                        if (section_text > header_text) {
                            char num_buf[64];
                            size_t num_len = section_text - header_text;
                            if (num_len >= sizeof(num_buf)) num_len = sizeof(num_buf) - 1;
                            strncpy(num_buf, header_text, num_len);
                            num_buf[num_len] = '\0';
                            sb_append(output, num_buf);
                        }
                        sb_append(output, "<a name=\"");
                        sb_append(output, anchor_buf);
                        sb_append(output, "\"></a>");
                        sb_append(output, section_text);
                        sb_append(output, "</h2>\n\n");
                    } else if (level == 4) {  // #### -> <h3>
                        // Generate anchor from full header text
                        char anchor_buf[256];
                        const char *p_anchor = header_text;
                        int j = 0;

                        for (int i = 0; p_anchor[i] && j < 255; i++) {
                            char c = p_anchor[i];
                            if (isalnum(c)) {
                                anchor_buf[j++] = tolower(c);
                            } else if ((isspace(c) || c == '.') && j > 0 && anchor_buf[j-1] != '-') {
                                anchor_buf[j++] = '-';
                            }
                        }
                        // Remove trailing dash
                        if (j > 0 && anchor_buf[j-1] == '-') j--;
                        anchor_buf[j] = '\0';

                        // Extract section number and text
                        const char *p_num = header_text;
                        const char *section_text = header_text;

                        // Skip section number (digits and dots)
                        while (*p_num && (isdigit(*p_num) || *p_num == '.')) {
                            p_num++;
                        }
                        // Skip spaces after number
                        while (*p_num == ' ') {
                            section_text = p_num + 1;
                            p_num++;
                        }

                        sb_append(output, "<h3>");
                        // Output section number if present
                        if (section_text > header_text) {
                            char num_buf[64];
                            size_t num_len = section_text - header_text;
                            if (num_len >= sizeof(num_buf)) num_len = sizeof(num_buf) - 1;
                            strncpy(num_buf, header_text, num_len);
                            num_buf[num_len] = '\0';
                            sb_append(output, num_buf);
                        }
                        sb_append(output, "<a name=\"");
                        sb_append(output, anchor_buf);
                        sb_append(output, "\"></a>");
                        sb_append(output, section_text);
                        sb_append(output, "</h3>\n\n");
                    }
                    continue;
                }
            }

            // Otherwise process as paragraph
            goto process_line;
        }

        // Ordered list
        if (isdigit(trimmed[0]) && strstr(trimmed, ". ") == trimmed + 1) {
            if (in_paragraph) {
                sb_append(output, "</p>\n\n");
                in_paragraph = false;
            }

            sb_append(output, "<ol>\n");

            // Process list items
            bool first_item = true;
            while (*p || first_item) {
                if (!first_item) {
                    // Get next line
                    i = 0;
                    while (*p && *p != '\n' && i < MAX_LINE - 1) {
                        line[i++] = *p++;
                    }
                    line[i] = '\0';
                    if (*p == '\n') p++;
                    trimmed = trim(line);
                }
                first_item = false;

                // Check if we've hit a blank line
                if (strlen(trimmed) == 0) {
                    break;
                }

                // Check if this is still a list item
                if (!(isdigit(trimmed[0]) && strchr(trimmed, '.'))) {
                    // Not a list item anymore
                    break;
                }

                // Accumulate the full list item content (may span multiple lines)
                StringBuilder *item = sb_create();
                char *item_text = strchr(trimmed, ' ') + 1;
                sb_append(item, item_text);

                // Continue reading continuation lines
                while (*p) {
                    // Peek at next line
                    i = 0;
                    const char *peek_start = p;
                    while (*p && *p != '\n' && i < MAX_LINE - 1) {
                        line[i++] = *p++;
                    }
                    line[i] = '\0';
                    if (*p == '\n') p++;

                    char *next_trimmed = trim(line);

                    // Stop if blank line, or starts with number., or starts with -,  or starts with #
                    if (strlen(next_trimmed) == 0 ||
                        (isdigit(next_trimmed[0]) && strchr(next_trimmed, '.')) ||
                        (next_trimmed[0] == '-' && next_trimmed[1] == ' ') ||
                        next_trimmed[0] == '#') {
                        // Put back the line by rewinding
                        p = peek_start;
                        break;
                    }

                    // This is a continuation line
                    sb_append(item, " ");
                    sb_append(item, next_trimmed);
                }

                // Output the complete list item
                sb_append(output, "\t<li>");
                convert_inline_md(item->text, output);
                sb_append(output, "</li>\n");
                sb_free(item);
            }

            sb_append(output, "</ol>\n\n");

            // The current line in 'trimmed' needs to be processed
            if (strlen(trimmed) == 0) continue;

            // Process the non-list line that ended the list
            goto process_line;
        }

process_line:
        // Regular paragraph - accumulate lines until we hit a blank line or special element
        if (!in_paragraph) {
            sb_append(output, "<p>\n");
            in_paragraph = true;
        }

        // Accumulate paragraph content - preserve line breaks for 80-char wrapping
        StringBuilder *para = sb_create();
        bool first_para_line = true;

        while (*p && strlen(trimmed) > 0 && trimmed[0] != '#' &&
               !(trimmed[0] == '-' && trimmed[1] == ' ') &&
               !(strncmp(trimmed, "```", 3) == 0) &&  // Code fence, not single backtick
               !(strncmp(trimmed, "---", 3) == 0) &&
               !(isdigit(trimmed[0]) && strstr(trimmed, ". ") == trimmed + 1)) {

            if (!first_para_line) {
                sb_append(para, "\n");  // Preserve line breaks instead of joining with space
            }
            first_para_line = false;

            sb_append(para, trimmed);

            // Get next line
            i = 0;
            while (*p && *p != '\n' && i < MAX_LINE - 1) {
                line[i++] = *p++;
            }
            line[i] = '\0';
            if (*p == '\n') p++;
            if (!*p) break;  // End of file
            trimmed = trim(line);

            // Stop if next line is indented code
            if ((line[0] == ' ' && line[1] == ' ' && line[2] == ' ' && line[3] == ' ') ||
                line[0] == '\t') {
                break;
            }
        }

        // Process the accumulated paragraph - newlines are treated as spaces in HTML
        convert_inline_md(para->text, output);
        sb_free(para);

        // Close paragraph
        sb_append(output, "</p>\n\n");
        in_paragraph = false;

        // The current line in trimmed might be a new element, process it
        if (strlen(trimmed) > 0) {
            goto process_line_start;
        }
    }

    if (in_paragraph) {
        sb_append(output, "</p>\n");
    }
}

void print_usage(const char *prog_name) {
    printf("Usage: %s <input_md_file> <output_php_file>\n\n", prog_name);
    printf("Convert Markdown to PHP/HTML.\n\n");
    printf("ARGUMENTS:\n");
    printf("  input_md_file     Input Markdown file (required)\n");
    printf("  output_php_file   Output PHP file (required)\n\n");
    printf("EXAMPLE:\n");
    printf("  %s api.md docs/api.php\n", prog_name);
}

int main(int argc, char *argv[]) {
    if (argc != 3) {
        print_usage(argv[0]);
        return 1;
    }

    const char *input_file = argv[1];
    const char *output_file = argv[2];

    printf("Converting Markdown to HTML/PHP...\n");
    printf("Input: %s\n", input_file);
    printf("Output: %s\n\n", output_file);

    // Read input file
    FILE *fp = fopen(input_file, "r");
    if (!fp) {
        fprintf(stderr, "Error: Cannot open file %s\n", input_file);
        return 1;
    }

    StringBuilder *content = sb_create();
    char line[MAX_LINE];
    while (fgets(line, sizeof(line), fp)) {
        sb_append(content, line);
    }
    fclose(fp);

    // Convert to HTML
    StringBuilder *html = sb_create();

    // Add PHP header
    sb_append(html, "<?php\n");
    sb_append(html, "require(\"../template.php\");\n\n");
    sb_append(html, "# Now set the title of the page:\n");
    sb_append(html, "setPageTitle(\"API Documentation\");\n\n");
    sb_append(html, "# Make the header.\n");
    sb_append(html, "generateHeader($_SERVER['PHP_SELF']);\n");
    sb_append(html, "?>\n\n");

    // Add title section (from original docs/api.php)
    sb_append(html, "<p></p>\n");
    sb_append(html, "<div style=\"font-size: 1.6em; font-weight: bold; text-align: center;\">\n");
    sb_append(html, "The Open-Source<br/>\n");
    sb_append(html, "OpenGL Utility Toolkit<br/>\n");
    sb_append(html, "(<i>freeglut</i> 3.0.0)<br/>\n");
    sb_append(html, "Application Programming Interface\n");
    sb_append(html, "</div>\n\n");
    sb_append(html, "<p></p>\n");
    sb_append(html, "<div style=\"font-size: 1.6em; font-weight: bold; text-align: center;\">\n");
    sb_append(html, "Version 4.0\n");
    sb_append(html, "</div>\n\n");
    sb_append(html, "<p></p>\n");
    sb_append(html, "<div style=\"font-size: 1.2em; font-weight: bold; text-align: center;\">\n");
    sb_append(html, "The <i>freeglut</i> Programming Consortium<br/>\n");
    sb_append(html, "January, 2013\n");
    sb_append(html, "</div>\n\n");
    sb_append(html, "<p>\n");
    sb_append(html, "OpenGL is a trademark of Silicon Graphics, Inc. X Window System is a trademark\n");
    sb_append(html, "of X Consortium, Inc.  Spaceball is a registered trademark of Spatial\n");
    sb_append(html, "Systems Inc.\n");
    sb_append(html, "</p>\n\n");
    sb_append(html, "<p>\n");
    sb_append(html, "The authors have taken care in preparation of this documentation but make\n");
    sb_append(html, "no expressed or implied warranty of any kind and assumes no responsibility\n");
    sb_append(html, "for errors or omissions. No liability is assumed for incidental or consequential\n");
    sb_append(html, "damages in connection with or arising from the use of information or programs\n");
    sb_append(html, "contained herein.\n");
    sb_append(html, "</p>\n\n");

    // Convert markdown content
    markdown_to_html(content->text, html);

    // Add PHP footer
    sb_append(html, "\n<?php generateFooter(); ?>\n");

    // Write output
    FILE *out = fopen(output_file, "w");
    if (!out) {
        fprintf(stderr, "Error: Cannot create output file %s\n", output_file);
        sb_free(content);
        sb_free(html);
        return 1;
    }

    fprintf(out, "%s", html->text);
    fclose(out);

    printf("Conversion complete!\n");
    printf("Generated: %s (%zu bytes)\n", output_file, html->size);

    sb_free(content);
    sb_free(html);

    return 0;
}
