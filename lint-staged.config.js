module.exports = {
    '**/*.php': ['php ./tools/vendor/bin/php-cs-fixer fix --config .php_cs'],
};
