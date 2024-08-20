{
    "name": "vendor_name/package_name",
    "type": "library",
    "homepage": "https://example.com",
    "license": "GPL-3.0+",
    "version": "1.0.0",
    "autoload": {
        "psr-4": {
            "VendorName\\PackageName\\": "src/"
        }
    },
    "authors": [
        {
            "name": "John Doe",
            "email": "john.doe@example.com"
        }
    ],
    "minimum-stability": "stable",
    "require": {
        "ephect-io/framework": "dev-develop"
    },
    "bin": [
        "bin/vendor-name_package-name_install.sh",
        "bin/vendor-name_package-name_install.bat"
    ]
}
