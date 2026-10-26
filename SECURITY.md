# Security Policy

## Supported Versions

We actively support the following versions of PHP Feed with security updates:

| Version | PHP  | Supported          |
| ------- | ---- | ------------------ |
| 1.1.x   | 8.3+ | :white_check_mark: |
| 1.0.x   | 8.2+ | :white_check_mark: until PHP 8.2 EOL (31 Dec 2026) |

Runtime PHP must be a patched build. PHP2Shell ([CVE-2026-109618](https://www.cve.org/CVERecord?id=CVE-2026-109618), [CVE-2026-109619](https://www.cve.org/CVERecord?id=CVE-2026-109619)) is in the PHP engine, not in this library. Upgrade the interpreter to **8.5.12+**, **8.4.27+**, **8.3.36+**, or **8.2.35+**. There is no application-level workaround; see the [PHP Foundation advisory](https://thephp.foundation/blog/2026/10/21/todays-php-security-release-and-how-were-strengthening-php-security/) and [CISA alert](https://www.cisa.gov/news-events/alerts/2026/10/21/critical-vulnerabilities-php-require-immediate-patching).

PHP 8.3 remains in upstream security support until **31 December 2027**. PHP 8.2 ends on **31 December 2026**.

## Reporting a Vulnerability

We take security seriously. If you discover a security vulnerability in PHP Feed, please report it responsibly.

### How to Report

Please **DO NOT** open a public GitHub issue for security vulnerabilities. Instead, please:

1. **Email us directly** at: `contact@rumenx.com`
2. **Include the following information:**
   - A clear description of the vulnerability
   - Steps to reproduce the issue
   - Potential impact of the vulnerability
   - Any suggested fixes (if you have them)

### What to Expect

- **Acknowledgment**: We will acknowledge receipt of your report within 48 hours
- **Investigation**: We will investigate and assess the vulnerability within 5 business days
- **Updates**: We will keep you informed of our progress throughout the process
- **Resolution**: We aim to resolve security issues within 30 days
- **Credit**: With your permission, we will credit you in our security advisory

### Security Best Practices

When using PHP Feed in your applications:

- Keep the package updated to the latest version
- Keep the PHP runtime itself updated. Engine issues such as PHP2Shell are not fixable from Composer packages
- Pass only `atom` or `rss` to `render()` unless you set a custom view yourself. Do not pass request input as the format or as `Feed::link()` attributes without validation
- Validate and sanitize all user input before passing to feed methods
- Use proper authentication and authorization for feed endpoints
- Consider rate limiting for public feed endpoints
- Regularly review your dependencies for security updates

### Disclosure Policy

- We will coordinate with you on the timing of any public disclosure
- We prefer to disclose vulnerabilities after a fix is available
- We will publish security advisories for significant vulnerabilities

Thank you for helping to keep PHP Feed secure!
