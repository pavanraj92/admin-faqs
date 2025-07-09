# Admin FAQ Manager

This package provides an Admin FAQ Manager for managing Frequently Asked Questions (FAQs) within your application.

## Features

- Create, edit, and delete FAQ entries
- Organize FAQs by categories
- WYSIWYG editor support for answers
- SEO-friendly URLs and metadata for FAQ pages
- User permissions and access control

## Usage

1. **Create**: Add a new FAQ with question and answer.
2. **Read**: View all FAQs in a paginated list.
3. **Update**: Edit FAQ information.
4. **Delete**: Remove FAQs that are no longer needed.

## Example Endpoints

| Method | Endpoint      | Description        |
|--------|---------------|--------------------|
| GET    | `/faqs`       | List all faqs      |
| POST   | `/faqs`       | Create a new faq   |
| GET    | `/faqs/{id}`  | Get faq details    |
| PUT    | `/faqs/{id}`  | Update a faq       |
| DELETE | `/faqs/{id}`  | Delete a faq       |

## Requirements

- PHP 8.2+
- Laravel Framework

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
    php artisan faq:publish --force

    composer dump-autoload
    
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
```

## Customization

You can customize views, routes, and permissions by editing the configuration file.

## License

This package is open-sourced software licensed under the Dotsquares.write code in the readme.md file regarding to the admin/faq manager
