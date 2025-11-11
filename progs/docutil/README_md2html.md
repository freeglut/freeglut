# md2html - Markdown to PHP/HTML Converter

Converts the freeglut API markdown documentation back to PHP/HTML format.

## Usage

```bash
./md2html <input_file> <output_file>
```

**Arguments:**
- `input_file`: The input Markdown file to convert.
- `output_file`: The path for the output PHP/HTML file.

## Example

```bash
./md2html api-manual-additions.md docs/api.php
```

## What it does

- **Header Conversion**: Converts markdown headers to HTML headers with anchor tags:
  - `##` → `<h1>` with CamelCase anchors (e.g., `<h1>14. <a name="FontRendering"></a>Font Rendering Functions</h1>`)
  - `###` → `<h2>` with lowercase-hyphenated anchors (e.g., `<h2>3.2 <a name="32-conventions"></a>Conventions</h2>`)
  - `####` → `<h3>` with lowercase-hyphenated anchors (e.g., `<h3>3.2.1 <a name="321-window-placement-and-size"></a>Window placement and size</h3>`)
  - Anchor format strips periods from section numbers (e.g., "3.2" becomes "32")
- **Table of Contents**: Converts the Table of Contents from markdown list format to nested HTML `<ol>` lists, limiting TOC depth to 2 levels (main sections and subsections only).
  - Top-level TOC links use CamelCase anchors to match the original docs/api.php format
  - Special handling for sections like "Font Rendering Functions" → "FontRendering" to match original naming conventions
- **Inline Formatting**: Translates common Markdown formatting to HTML:
  - `` `code` `` → `<tt>code</tt>`
  - `**bold**` → `<b>bold</b>`
  - `*italic*` → `<i>italic</i>`
  - `[link](url)` → `<a href="url">link</a>` - Preserves both external and internal anchor links
- **Code Blocks**: Converts fenced code blocks (` ``` `) to `<p><tt>...</tt></p>`.
- **Nested Lists**: Handles nested lists within list items, detecting them by checking for text ending with a colon followed by dashes, and preserves multi-paragraph content within list items.
- **PHP Template**: Adds PHP header and footer for the freeglut website template.

## Building

```bash
cc -o md2html md2html.c
```

## Features

### Nested List Detection

The converter intelligently distinguishes between:
- Nested lists (e.g., "However, on X11/UNIX systems, the window manager may:" followed by bulleted items)
- New top-level list items (e.g., separate sections like "Window positioning (querying)")

Detection is based on whether the preceding text line ends with a colon.

### TOC Depth Limiting

The Table of Contents only includes 2 levels:
- Level 1: Main sections (e.g., "4. Initialization Functions")
- Level 2: Subsections (e.g., "4.1 glutInit")
- Level 3+ items are excluded from the TOC

### Multi-line List Items

List items can span multiple paragraphs and include nested lists:
```markdown
- **Item:** First paragraph text.

  Continuation paragraph after blank lines.

  - Nested item 1
  - Nested item 2

  Final paragraph.
```

This converts to proper HTML with all content within a single `<li>` tag.

## Important Notes

### Anchor Format Compatibility

To ensure proper round-trip conversion between HTML and Markdown:

1. **docs/api.php should use lowercase-hyphenated anchors** for h2 and h3 sections (e.g., `#32-conventions`, `#321-window-placement-and-size`)
2. **h1 sections use CamelCase anchors** as in the original format (e.g., `#FontRendering`, `#StateSetting`)
3. Internal links in docs/api.php should use the markdown-compatible format

This allows bidirectional conversion:
- `html2md` preserves these anchors when converting to Markdown
- `md2html` generates matching anchors when converting back to HTML

### Section Numbering

The converter maintains consistent section numbering. When the "Contents" section is skipped during HTML→Markdown conversion (`--skip-h1-header "Contents"`), it's still included in the TOC to ensure that:
- Section 1 = Contents (TOC only)
- Section 2 = Introduction
- Section 3 = Background
- etc.

This ensures the generated PHP file matches the original numbering scheme.
