# Documentation Conversion Tools

This folder includes two command-line tools for converting the freeglut API documentation between HTML/PHP and Markdown formats:

## html2md - HTML to Markdown Converter

Converts the API documentation from PHP/HTML to Markdown for easier editing.

```bash
./html2md --skip-h1-header "Contents" --generate-toc --top-level-header "freeglut API" docs/api.php api.md
```

See [README_html2md.md](README_html2md.md) for detailed documentation.

## md2html - Markdown to PHP/HTML Converter

Converts the API documentation from Markdown back to PHP/HTML for the website.

```bash
./md2html api.md docs/api.php
```

See [README_md2html.md](README_md2html.md) for detailed documentation.

## Building the Tools

```bash
cc -o html2md html2md.c
cc -o md2html md2html.c
```

## Workflow

The tools support bidirectional conversion, allowing you to:

1. Convert `docs/api.php` to `api.md` for editing
2. Make changes to the Markdown version
3. Convert back to `docs/api.php` for the website

The conversion preserves:
- Section numbering
- Internal anchor links
- Formatting (bold, italic, code, lists)
- Table of contents structure
- Code blocks

**Important**: To ensure proper round-trip conversion, `docs/api.php` should use markdown-compatible anchor formats (lowercase-hyphenated for h2/h3 sections like `#32-conventions`, CamelCase for h1 sections like `#FontRendering`).
