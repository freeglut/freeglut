# html2md - HTML to Markdown Converter

Converts freeglut API documentation from PHP/HTML format to Markdown. This tool is specifically designed to reverse the process of `md2html`, allowing for easier maintenance of the documentation in Markdown format.

## Usage

```bash
./html2md [OPTIONS] <input_file> <output_file>
```

**Options:**
- `--skip-h1-header <text>`: Skip an H1 header containing the specified text, and all content until the next H1. This is useful for excluding sections like a table of contents from the output.
- `--generate-toc`: Automatically generate a Table of Contents in Markdown format from the headers found in the HTML.
- `--top-level-header <text>`: Add a top-level H1 header (e.g., `# My Document`) to the beginning of the output Markdown file.
- `--help`: Display the help message.

**Arguments:**
- `input_file`: The input PHP/HTML file to convert.
- `output_file`: The path for the output Markdown file.

## Example

```bash
./html2md --skip-h1-header "Contents" --generate-toc --top-level-header "freeglut API" docs/api.php api.md
```

## What it does

- **Header Conversion**: Converts `<h1>`, `<h2>`, and `<h3>` HTML tags to their corresponding Markdown header levels (`##`, `###`, `####`).
- **Code Blocks**: Converts both `<pre>` blocks and the `<p><tt>...` pattern used for code into Markdown fenced code blocks (```c ... ```).
- **Inline Formatting**: Translates common inline HTML tags to Markdown:
  - `<a>` → `[text](url)` - Handles anchor tags with any whitespace (space, newline, tab) after `<a`
  - `<b>`, `<strong>` → `**bold**`
  - `<i>`, `<em>` → `*italic*`
  - `<u>` → `__underline__`
  - `<tt>`, `<code>` → ``code``
- **Lists**: Converts ordered (`<ol>`) and unordered (`<ul>`) lists into their Markdown equivalents, preserving nesting.
- **Table of Contents**: If requested, generates a Markdown Table of Contents from the document's headers.
  - **Important**: When using `--skip-h1-header`, the skipped header is still included in the TOC to maintain section numbering consistency. Only the header's body content is omitted from the output.
- **Anchor Links**: Preserves internal anchor links from the HTML source (e.g., `href="#32-conventions"` becomes `[text](#32-conventions)` in Markdown).
- **PHP/HTML Cleanup**: Strips `<?php ... ?>` blocks and `<!-- ... -->` comments.
- **Section Skipping**: Can exclude entire sections of the document based on header text.

## Building

```bash
cc -o html2md html2md.c
```
