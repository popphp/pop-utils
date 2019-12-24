<?php

namespace Pop\Utils\Test;

use Pop\Utils\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{

    public function testCreateSlug()
    {
        $this->assertEquals('hello-world-home-page', StringHelper::createSlug('Hello World | Home Page'));
    }

    public function testCreateLinks()
    {
        $html = <<<HTML
Testing test@test.com here is a website
https://www.google.com/
Here is another website http://test.com and an FTP site at ftp://ftp.test.com
HTML;

        $links = StringHelper::createLinks($html, ['target' => '_blank']);
        $this->assertContains('<a target="_blank" href="mailto:test@test.com">test@test.com</a>', $links);
        $this->assertContains('<a target="_blank" href="https://www.google.com/">https://www.google.com/</a>', $links);
        $this->assertContains('<a target="_blank" href="http://test.com">http://test.com</a>', $links);
        $this->assertContains('<a target="_blank" href="ftp://ftp.test.com">ftp://ftp.test.com</a>', $links);
    }

    public function testCreateRandom()
    {
        $this->assertEquals(6, strlen(StringHelper::createRandom(6)));
        $this->assertEquals(8, strlen(StringHelper::createRandom(8, StringHelper::LOWERCASE)));
        $this->assertEquals(10, strlen(StringHelper::createRandom(10, StringHelper::UPPERCASE)));
    }

    public function testCreateRandomAlphaNum()
    {
        $this->assertEquals(0, preg_match('/[!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', StringHelper::createRandomAlphaNum(6)));
        $this->assertEquals(0, preg_match('/[!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', StringHelper::createRandomAlphaNum(8, StringHelper::LOWERCASE)));
        $this->assertEquals(0, preg_match('/[!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', StringHelper::createRandomAlphaNum(10, StringHelper::UPPERCASE)));
    }

    public function testCreateRandomAlpha()
    {
        $this->assertEquals(0, preg_match('/[23456789!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', StringHelper::createRandomAlpha(6)));
        $this->assertEquals(0, preg_match('/[23456789!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', StringHelper::createRandomAlpha(8, StringHelper::LOWERCASE)));
        $this->assertEquals(0, preg_match('/[23456789!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', StringHelper::createRandomAlpha(10, StringHelper::UPPERCASE)));
    }

    public function testCaseConvert()
    {
        $this->assertEquals('titleCase', StringHelper::titleCaseToCamelCase('TitleCase'));
        $this->assertEquals('title-case', StringHelper::titleCaseToKebabCase('TitleCase'));
        $this->assertEquals('title-case', StringHelper::titleCaseToDash('TitleCase'));
        $this->assertEquals('title_case', StringHelper::titleCaseToSnakeCase('TitleCase'));
        $this->assertEquals('title_case', StringHelper::titleCaseToUnderscore('TitleCase'));
        $this->assertEquals('Title\Case', StringHelper::titleCaseToNamespace('TitleCase'));
        $this->assertEquals('Title/Case', StringHelper::titleCaseToPath('TitleCase'));
        $this->assertEquals('title/case', StringHelper::titleCaseToPath('TitleCase', false));
        $this->assertEquals('Title/Case', StringHelper::titleCaseToUrl('TitleCase'));
        $this->assertEquals('title/case', StringHelper::titleCaseToUri('TitleCase', false));

        $this->assertEquals('CamelCase', StringHelper::camelCaseToTitleCase('camelCase'));
        $this->assertEquals('camel-case', StringHelper::camelCaseToKebabCase('camelCase'));
        $this->assertEquals('camel-case', StringHelper::camelCaseToDash('camelCase'));
        $this->assertEquals('camel_case', StringHelper::camelCaseToSnakeCase('camelCase'));
        $this->assertEquals('camel_case', StringHelper::camelCaseToUnderscore('camelCase'));
        $this->assertEquals('camel\Case', StringHelper::camelCaseToNamespace('camelCase'));
        $this->assertEquals('camel/Case', StringHelper::camelCaseToPath('camelCase'));
        $this->assertEquals('camel/case', StringHelper::camelCaseToPath('camelCase', false));
        $this->assertEquals('camel/Case', StringHelper::camelCaseToUrl('camelCase'));
        $this->assertEquals('camel/case', StringHelper::camelCaseToUri('camelCase', false));

        $this->assertEquals('DashedString', StringHelper::dashToTitleCase('dashed-string'));
        $this->assertEquals('dashedString', StringHelper::dashToCamelCase('dashed-string'));
        $this->assertEquals('dashed_string', StringHelper::dashToSnakeCase('dashed-string'));
        $this->assertEquals('dashed_string', StringHelper::dashToUnderscore('dashed-string'));
        $this->assertEquals('Dashed\String', StringHelper::dashToNamespace('dashed-string'));
        $this->assertEquals('Dashed/String', StringHelper::dashToPath('dashed-string'));
        $this->assertEquals('dashed/string', StringHelper::dashToPath('dashed-string', false));
        $this->assertEquals('Dashed/String', StringHelper::dashToUri('dashed-string'));
        $this->assertEquals('dashed/string', StringHelper::dashToUrl('dashed-string', false));

        $this->assertEquals('KebabString', StringHelper::kebabCaseToTitleCase('kebab-string'));
        $this->assertEquals('kebabString', StringHelper::kebabCaseToCamelCase('kebab-string'));
        $this->assertEquals('kebab_string', StringHelper::kebabCaseToSnakeCase('kebab-string'));
        $this->assertEquals('kebab_string', StringHelper::kebabCaseToUnderscore('kebab-string'));
        $this->assertEquals('Kebab\String', StringHelper::kebabCaseToNamespace('kebab-string'));
        $this->assertEquals('Kebab/String', StringHelper::kebabCaseToPath('kebab-string'));
        $this->assertEquals('kebab/string', StringHelper::kebabCaseToPath('kebab-string', false));
        $this->assertEquals('Kebab/String', StringHelper::kebabCaseToUri('kebab-string'));
        $this->assertEquals('kebab/string', StringHelper::kebabCaseToUrl('kebab-string', false));

        $this->assertEquals('UnderscoreString', StringHelper::underscoreToTitleCase('underscore_string'));
        $this->assertEquals('underscoreString', StringHelper::underscoreToCamelCase('underscore_string'));
        $this->assertEquals('underscore-string', StringHelper::underscoreToKebabCase('underscore_string'));
        $this->assertEquals('underscore-string', StringHelper::underscoreToDash('underscore_string'));
        $this->assertEquals('Underscore\String', StringHelper::underscoreToNamespace('underscore_string'));
        $this->assertEquals('Underscore/String', StringHelper::underscoreToPath('underscore_string'));
        $this->assertEquals('underscore/string', StringHelper::underscoreToPath('underscore_string', false));
        $this->assertEquals('Underscore/String', StringHelper::underscoreToUrl('underscore_string'));
        $this->assertEquals('underscore/string', StringHelper::underscoreToUri('underscore_string', false));

        $this->assertEquals('SnakeCaseString', StringHelper::snakeCaseToTitleCase('snake_case_string'));
        $this->assertEquals('snakeCaseString', StringHelper::snakeCaseToCamelCase('snake_case_string'));
        $this->assertEquals('snake-case-string', StringHelper::snakeCaseToKebabCase('snake_case_string'));
        $this->assertEquals('snake-case-string', StringHelper::snakeCaseToDash('snake_case_string'));
        $this->assertEquals('Snake\Case\String', StringHelper::snakeCaseToNamespace('snake_case_string'));
        $this->assertEquals('Snake/Case/String', StringHelper::snakeCaseToPath('snake_case_string'));
        $this->assertEquals('snake/case/string', StringHelper::snakeCaseToPath('snake_case_string', false));
        $this->assertEquals('Snake/Case/String', StringHelper::snakeCaseToUri('snake_case_string'));
        $this->assertEquals('snake/case/string', StringHelper::snakeCaseToUrl('snake_case_string', false));

        $this->assertEquals('NamespaceString', StringHelper::namespaceToTitleCase('Namespace\String'));
        $this->assertEquals('namespaceString', StringHelper::namespaceToCamelCase('Namespace\String'));
        $this->assertEquals('namespace-string', StringHelper::namespaceToKebabCase('Namespace\String'));
        $this->assertEquals('Namespace-String', StringHelper::namespaceToDash('Namespace\String', true));
        $this->assertEquals('namespace_string', StringHelper::namespaceToSnakeCase('Namespace\String'));
        $this->assertEquals('namespace_string', StringHelper::namespaceToUnderscore('Namespace\String'));
        $this->assertEquals('Namespace/String', StringHelper::namespaceToPath('Namespace\String'));
        $this->assertEquals('namespace/string', StringHelper::namespaceToPath('Namespace\String', false));
        $this->assertEquals('Namespace/String', StringHelper::namespaceToUrl('Namespace\String'));
        $this->assertEquals('namespace/string', StringHelper::namespaceToUri('Namespace\String', false));

        $this->assertEquals('PathString', StringHelper::pathToTitleCase('Path/String'));
        $this->assertEquals('pathString', StringHelper::pathToCamelCase('/Path/String'));
        $this->assertEquals('path-string', StringHelper::pathToKebabCase('Path/String'));
        $this->assertEquals('Path-String', StringHelper::pathToDash('Path/String', true));
        $this->assertEquals('path_string', StringHelper::pathToSnakeCase('Path/String'));
        $this->assertEquals('path_string', StringHelper::pathToUnderscore('Path/String'));
        $this->assertEquals('Path\String', StringHelper::pathToNamespace('Path/String'));

        $this->assertEquals('UrlString', StringHelper::uriToTitleCase('/url/string'));
        $this->assertEquals('urlString', StringHelper::urlToCamelCase('url/string'));
        $this->assertEquals('url-string', StringHelper::uriToKebabCase('url/string'));
        $this->assertEquals('Url-String', StringHelper::urlToDash('url/string', true));
        $this->assertEquals('url_string', StringHelper::uriToSnakeCase('url/string'));
        $this->assertEquals('url_string', StringHelper::urlToUnderscore('url/string'));
        $this->assertEquals('Url\String', StringHelper::uriToNamespace('url/string'));
    }

}