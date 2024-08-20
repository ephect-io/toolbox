{
    "name": "{{fqPackageName}}",
    "type": "library",
    "homepage": "https://example.com",
    "license": "GPL-3.0+",
    "version": "{{version}}",
    "autoload": {
        "psr-4": {
            "{{fqPackageNamespace}}": "src/"
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
        "bin/{{snakeCasePackageName}}_install.sh",
        "bin/{{snakeCasePackageName}}_install.bat"
    ]
}
