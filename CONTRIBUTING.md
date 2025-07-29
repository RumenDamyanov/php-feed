# Contributing to PHP Feed

Thank you for considering contributing to PHP Feed! We welcome contributions from the community.

## Development Environment

### Prerequisites

- PHP 8.3+
- Composer
- Git

### Setup

1. Fork the repository on GitHub
2. Clone your fork locally:

   ```bash
   git clone https://github.com/YOUR_USERNAME/php-feed.git
   cd php-feed
   ```

3. Install dependencies:

   ```bash
   composer install
   ```

4. Create a new branch for your feature or bugfix:

   ```bash
   git checkout -b feature/your-feature-name
   ```

## Development Guidelines

### Code Style

- Follow PSR-12 coding standards
- Use strict typing (`declare(strict_types=1)`) where appropriate
- All classes and methods should have proper docblocks
- Use meaningful variable and method names

### Testing

- All new features must include tests
- Aim for 100% code coverage
- Run tests before submitting:

  ```bash
  composer test
  ```

- Run tests with coverage:

  ```bash
  composer test:coverage
  ```

- Run tests with HTML coverage report:

  ```bash
  composer test:coverage-html
  ```

- Watch tests during development:

  ```bash
  composer test:watch
  ```

### Code Quality

Run static analysis with PHPStan:

```bash
composer analyse
```

Check code style (PSR-12):

```bash
composer style
```

Fix code style automatically:

```bash
composer style:fix
```

Run all quality checks:

```bash
composer check
```

Run CI checks (used in GitHub Actions):

```bash
composer ci
```

### Architecture

- The package follows a dependency injection pattern
- Adapters implement framework-specific interfaces
- The core `Feed` class is framework-agnostic
- All properties should be private with public getters/setters

## Types of Contributions

### Bug Reports

When reporting bugs, please include:

- PHP version
- Framework version (if applicable)
- Steps to reproduce
- Expected vs actual behavior
- Code examples

### Feature Requests

Before submitting a feature request:

- Check if it already exists in issues
- Explain the use case and benefits
- Consider if it fits the package's scope
- Be willing to help implement it

### Pull Requests

1. **Create an issue first** for significant changes
2. **Write tests** for new functionality
3. **Update documentation** if needed
4. **Follow the existing code style**
5. **Keep commits focused** and write clear commit messages

### Pull Request Process

1. Ensure all tests pass
2. Update the README.md if needed
3. Add your changes to any relevant documentation
4. Submit the pull request with a clear description
5. Be responsive to feedback and requested changes

## Framework Integration

### Adding New Framework Support

If you want to add support for a new framework:

1. Create adapters implementing the four core interfaces:
   - `FeedCacheInterface`
   - `FeedConfigInterface`
   - `FeedResponseInterface`
   - `FeedViewInterface`

2. Place adapters in `src/Rumenx/Feed/YourFramework/`
3. Add comprehensive unit tests
4. Update documentation with usage examples
5. Consider adding integration tests

### Adapter Guidelines

- Keep adapters lightweight and focused
- Handle framework-specific error cases
- Follow the framework's conventions
- Document any special requirements

## Documentation

- Keep the README.md up to date
- Add docblocks to all public methods
- Include usage examples for new features
- Update CHANGELOG.md for significant changes

## Community Guidelines

- Be respectful and constructive
- Help others learn and contribute
- Follow our community standards
- Ask questions if something is unclear

## Security

If you discover any security vulnerabilities, please follow our [Security Policy](SECURITY.md).

## Getting Help

- Open an issue for questions
- Check existing issues and documentation first
- Be specific about your problem or question
- Review our [README](README.md) for usage examples

## Recognition

Contributors will be recognized in:

- GitHub contributors list
- Release notes for significant contributions
- README.md acknowledgments section

## Useful Links

- [README.md](README.md) - Main documentation
- [SECURITY.md](SECURITY.md) - Security policy
- [LICENSE.md](LICENSE.md) - License information
- [GitHub Issues](https://github.com/RumenDamyanov/php-feed/issues) - Bug reports and feature requests
- [GitHub Actions](https://github.com/RumenDamyanov/php-feed/actions) - CI/CD pipeline

Thank you for helping make PHP Feed better!
