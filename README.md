# CARRÉ ÉTUDE MAROC

Static French/English website for InterServer Apache hosting, with PHP contact form.

## Deploy

Copy this repository's contents into the domain document root, including `.htaccess` and `_next/`. Select PHP 8.1+. No Node.js process, package installation or build command is required on InterServer.

- Production URL: https://carreetude.com
- Default language: French; English: `/en/`
- Entry point: `index.html`
- Contact: `contact.php` using server PHP `mail()` to `contact@carreetude.com`

Read [INSTALLATION.txt](INSTALLATION.txt) before switching the domain. Contact inbox delivery must be verified on the real host. No SMTP credentials are included.

## Validation

Static export and TypeScript passed. All local HTML asset/link references resolved. PHP execution, real email delivery and a new browser visual review have not been performed in this migration.

This repository contains the compiled hosting files. Edit the original site source and regenerate the export for design changes. GitHub does not automatically deploy to InterServer unless a separate deployment integration is configured.
