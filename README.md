# Admin FAQ Manager

This package provides an Admin FAQ Manager for managing Frequently Asked Questions (FAQs) within your application.

## Features

- Create, edit, and delete FAQ entries
- Organize FAQs by categories
- WYSIWYG editor support for answers
- SEO-friendly URLs and metadata for FAQ pages
- User permissions and access control

## Update `composer.json`

Add the following to your `composer.json` to use the package from a local path:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-faqs.git"
    }
]
```

## Installation

```bash
composer require admin/faqs --dev
```

## Usage

1. Publish the configuration and migration files:
    ```bash
    php artisan vendor:publish --tag=faq
    php artisan migrate
    ```
2. Access the FAQ manager from your admin dashboard.

## CRUD Example

```php
// Creating a new FAQ
$faq = new Faq();
$faq->question = 'How do I reset my password?';
$faq->answer = '<p>You can reset your password by clicking "Forgot Password" on the login page.</p>';
$faq->save();

// Updating an FAQ
$faq = Faq::find(1);
$faq->answer = '<p>Updated answer content.</p>';
$faq->save();

// Deleting an FAQ
$faq = Faq::find(1);
$faq->delete();
```

## Customization

You can customize views, routes, and permissions by editing the configuration file.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
