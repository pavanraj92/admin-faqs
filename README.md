# Admin FAQ Manager

This package provides an Admin FAQ Manager for managing Frequently Asked Questions (FAQs) within your application.

---

## Features

- Create, edit, and delete FAQ entries
- Organize FAQs by categories
- CKeditor support for answers
- SEO-friendly URLs and metadata for FAQ pages
- User permissions and access control

---

## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

---

## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-faqs.git"
    }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/faqs:@dev
    ```

### 3. Publish assets
    ```bash
    php artisan faq:publish --force
    ```
---

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

---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // Admin FAQ routes here
});
```
---

## Database Tables

- `faqs` - Stores FAQ information

---

## License

This package is open-sourced software licensed under the MIT license.
