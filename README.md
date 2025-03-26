# Huckabuild CMS

Huckabuild is a modern PHP Content Management System built with SQLite, designed to be lightweight, fast, and developer-friendly.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/huckabuild/huckabuild.svg?style=flat-square)](https://packagist.org/packages/huckabuild/huckabuild)
[![Total Downloads](https://img.shields.io/packagist/dt/huckabuild/huckabuild.svg?style=flat-square)](https://packagist.org/packages/huckabuild/huckabuild)
[![License](https://img.shields.io/packagist/l/huckabuild/huckabuild.svg?style=flat-square)](https://packagist.org/packages/huckabuild/huckabuild)

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Project Structure](#project-structure)
- [Development](#development)
- [Contributing](#contributing)

## Requirements

- PHP 8.1 or higher
- Composer
- SQLite3
- PHP SQLite extension enabled

## Installation

### Creating a New Project

You can create a new Huckabuild project using Composer:

```bash
composer create-project huckabuild/huckabuild my-huckabuild-site
```

### Adding to an Existing Project

If you want to add Huckabuild to an existing project, you can install it via Composer:

```bash
composer require huckabuild/huckabuild
```

### Post-Installation Steps

1. Navigate to your project directory:
```bash
cd my-huckabuild-site
```

2. The installation process will automatically create a `.env` file from `.env.example`. Make sure to review and update the environment variables as needed.

3. Start the development server:
```bash
composer dev
```

Your Huckabuild site should now be running at `http://localhost:8000`

## Quick Start

### Initial Setup

After installation, you'll need to:

1. Configure your database settings in `.env`
2. Run database migrations (if any):
```bash
php huckabuild migrate
```

### Common Commands

- Start development server: `composer dev`
- Create a new page: `php huckabuild make:page`
- Create a new template: `php huckabuild make:template`

> **Note:** Make sure you're running all commands from the root directory of your Huckabuild installation.

## Project Structure 

```
huckabuild/
├── app/                    # Application core files
│   ├── Controllers/       # Request handlers
│   ├── Models/           # Database models
│   └── Middleware/       # Request/Response middleware
├── public/                # Web server root directory
│   └── index.php         # Application entry point
├── resources/             # Frontend resources
│   └── views/            # Twig templates
│       ├── admin/        # Admin panel templates
│       ├── layouts/      # Base layout templates
│       └── pages/        # Public page templates
├── config/               # Configuration files
├── database/             # Database files and migrations
├── storage/              # File uploads and cache
├── tests/                # Test files
├── .env                  # Environment configuration
├── .env.example          # Example environment configuration
└── composer.json         # PHP dependencies and scripts
```

## Development

### Local Development Server

Start the local development server using:
```bash
composer dev
```

### Key Directories

- **app/**: Contains the core application code
- **public/**: The only directory that should be publicly accessible
- **storage/**: Must be writable by your web server
- **config/**: Contains all configuration files

### Common Gotchas

1. **Permissions**: Ensure the `storage/` directory is writable by your web server
2. **SQLite Database**: By default, the SQLite database file is stored in `database/`. Make sure this directory is writable
3. **Environment Variables**: Always check `.env` file exists and is properly configured
4. **URL Rewriting**: Ensure your web server is configured to handle URL rewriting for clean URLs

## Contributing

We welcome contributions to Huckabuild! Here's how you can help:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Setup for Contributors

1. Clone the repository:
```bash
git clone https://github.com/sam-huckaby/huckabuild.git
```

2. Install dependencies:
```bash
composer install
```

3. Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```

4. Configure your local environment variables

### Coding Standards

- Follow PSR-12 coding standards
- Write tests for new features
- Document new features and changes
- Keep pull requests focused on a single feature or fix

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For bugs and feature requests, please use the [GitHub Issues](https://github.com/sam-huckaby/huckabuild/issues) page.

---

Built with ♥ by Sam Huckaby

