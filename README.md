# ZegacLabs Dev Accelerator

A Magento 2 module offering a collection of development tools and utilities designed to improve productivity, simplify debugging, and speed up the development workflow.


## Features

- **Template Debugger** - Shows template file paths in HTML comments
- **Container Debugger** - Marks layout containers with HTML comments
- **Layout Handles Logger** - Logs page handles to browser console

## Installation

```bash
composer require --dev zegaclabs/module-dev-accelerator
php bin/magento module:enable ZegacLabs_DevAccelerator
php bin/magento setup:upgrade
php bin/magento cache:clean
```

## Configuration

**Admin Panel → Stores → Configuration → ZegacLabs → Dev Accelerator**

### Options
- **Enable Dev Accelerator** - Master switch (requires Developer mode)
- **Enable Template Comments** - Show template paths in HTML
- **Enable Container Comments** - Show container boundaries in HTML
- **Enable Layout Handles Logger** - Log handles to browser console

## Usage

### Template Comments
1. Enable feature in admin config
2. View page source (Right-click → View Page Source)
3. Look for `<!-- TEMPLATE START/END: path -->` comments

### Container Comments
1. Enable feature in admin config
2. View page source
3. Look for `<!-- CONTAINER START/END: name -->` comments

### Layout Handles Logger
1. Enable feature in admin config
2. Open browser Developer Tools (F12)
3. Check Console tab for "Page Layout Handles" section
