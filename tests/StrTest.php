<?php

namespace Pop\Utils\Test;

use Pop\Utils\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{

    public function testCreateSlug()
    {
        $this->assertEquals('hello-world-home-page', Str::createSlug('Hello World | Home Page'));
    }

    public function testCreateLinks()
    {
        $html = <<<HTML
Testing test@test.com here is a website
https://www.google.com/
Here is another website http://test.com and an FTP site at ftp://ftp.test.com
HTML;

        $links = Str::createLinks($html, ['target' => '_blank']);
        $this->assertStringContainsString('<a target="_blank" href="mailto:test@test.com">test@test.com</a>', $links);
        $this->assertStringContainsString('<a target="_blank" href="https://www.google.com/">https://www.google.com/</a>', $links);
        $this->assertStringContainsString('<a target="_blank" href="http://test.com">http://test.com</a>', $links);
        $this->assertStringContainsString('<a target="_blank" href="ftp://ftp.test.com">ftp://ftp.test.com</a>', $links);
    }

    public function testCreateRandom()
    {
        $this->assertEquals(6, strlen(Str::createRandom(6)));
        $this->assertEquals(8, strlen(Str::createRandom(8, Str::LOWERCASE)));
        $this->assertEquals(10, strlen(Str::createRandom(10, Str::UPPERCASE)));
    }

    public function testCreateRandomAlphaNum()
    {
        $this->assertEquals(0, preg_match('/[!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', Str::createRandomAlphaNum(6)));
        $this->assertEquals(0, preg_match('/[!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', Str::createRandomAlphaNum(8, Str::LOWERCASE)));
        $this->assertEquals(0, preg_match('/[!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', Str::createRandomAlphaNum(10, Str::UPPERCASE)));
    }

    public function testCreateRandomAlpha()
    {
        $this->assertEquals(0, preg_match('/[23456789!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', Str::createRandomAlpha(6)));
        $this->assertEquals(0, preg_match('/[23456789!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', Str::createRandomAlpha(8, Str::LOWERCASE)));
        $this->assertEquals(0, preg_match('/[23456789!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', Str::createRandomAlpha(10, Str::UPPERCASE)));
    }

    public function testCaseConvert()
    {
        $this->assertEquals('titleCase', Str::titleCaseToCamelCase('TitleCase'));
        $this->assertEquals('title-case', Str::titleCaseToKebabCase('TitleCase'));
        $this->assertEquals('title-case', Str::titleCaseToDash('TitleCase'));
        $this->assertEquals('title_case', Str::titleCaseToSnakeCase('TitleCase'));
        $this->assertEquals('title_case', Str::titleCaseToUnderscore('TitleCase'));
        $this->assertEquals('Title\Case', Str::titleCaseToNamespace('TitleCase'));
        $this->assertEquals('Title/Case', Str::titleCaseToPath('TitleCase'));
        $this->assertEquals('title/case', Str::titleCaseToPath('TitleCase', false));
        $this->assertEquals('Title/Case', Str::titleCaseToUrl('TitleCase'));
        $this->assertEquals('title/case', Str::titleCaseToUri('TitleCase', false));

        $this->assertEquals('CamelCase', Str::camelCaseToTitleCase('camelCase'));
        $this->assertEquals('camel-case', Str::camelCaseToKebabCase('camelCase'));
        $this->assertEquals('camel-case', Str::camelCaseToDash('camelCase'));
        $this->assertEquals('camel_case', Str::camelCaseToSnakeCase('camelCase'));
        $this->assertEquals('camel_case', Str::camelCaseToUnderscore('camelCase'));
        $this->assertEquals('camel\Case', Str::camelCaseToNamespace('camelCase'));
        $this->assertEquals('camel/Case', Str::camelCaseToPath('camelCase'));
        $this->assertEquals('camel/case', Str::camelCaseToPath('camelCase', false));
        $this->assertEquals('camel/Case', Str::camelCaseToUrl('camelCase'));
        $this->assertEquals('camel/case', Str::camelCaseToUri('camelCase', false));

        $this->assertEquals('DashedString', Str::dashToTitleCase('dashed-string'));
        $this->assertEquals('dashedString', Str::dashToCamelCase('dashed-string'));
        $this->assertEquals('dashed_string', Str::dashToSnakeCase('dashed-string'));
        $this->assertEquals('dashed_string', Str::dashToUnderscore('dashed-string'));
        $this->assertEquals('Dashed\String', Str::dashToNamespace('dashed-string'));
        $this->assertEquals('Dashed/String', Str::dashToPath('dashed-string'));
        $this->assertEquals('dashed/string', Str::dashToPath('dashed-string', false));
        $this->assertEquals('Dashed/String', Str::dashToUri('dashed-string'));
        $this->assertEquals('dashed/string', Str::dashToUrl('dashed-string', false));

        $this->assertEquals('KebabString', Str::kebabCaseToTitleCase('kebab-string'));
        $this->assertEquals('kebabString', Str::kebabCaseToCamelCase('kebab-string'));
        $this->assertEquals('kebab_string', Str::kebabCaseToSnakeCase('kebab-string'));
        $this->assertEquals('kebab_string', Str::kebabCaseToUnderscore('kebab-string'));
        $this->assertEquals('Kebab\String', Str::kebabCaseToNamespace('kebab-string'));
        $this->assertEquals('Kebab/String', Str::kebabCaseToPath('kebab-string'));
        $this->assertEquals('kebab/string', Str::kebabCaseToPath('kebab-string', false));
        $this->assertEquals('Kebab/String', Str::kebabCaseToUri('kebab-string'));
        $this->assertEquals('kebab/string', Str::kebabCaseToUrl('kebab-string', false));

        $this->assertEquals('UnderscoreString', Str::underscoreToTitleCase('underscore_string'));
        $this->assertEquals('underscoreString', Str::underscoreToCamelCase('underscore_string'));
        $this->assertEquals('underscore-string', Str::underscoreToKebabCase('underscore_string'));
        $this->assertEquals('underscore-string', Str::underscoreToDash('underscore_string'));
        $this->assertEquals('Underscore\String', Str::underscoreToNamespace('underscore_string'));
        $this->assertEquals('Underscore/String', Str::underscoreToPath('underscore_string'));
        $this->assertEquals('underscore/string', Str::underscoreToPath('underscore_string', false));
        $this->assertEquals('Underscore/String', Str::underscoreToUrl('underscore_string'));
        $this->assertEquals('underscore/string', Str::underscoreToUri('underscore_string', false));

        $this->assertEquals('SnakeCaseString', Str::snakeCaseToTitleCase('snake_case_string'));
        $this->assertEquals('snakeCaseString', Str::snakeCaseToCamelCase('snake_case_string'));
        $this->assertEquals('snake-case-string', Str::snakeCaseToKebabCase('snake_case_string'));
        $this->assertEquals('snake-case-string', Str::snakeCaseToDash('snake_case_string'));
        $this->assertEquals('Snake\Case\String', Str::snakeCaseToNamespace('snake_case_string'));
        $this->assertEquals('Snake/Case/String', Str::snakeCaseToPath('snake_case_string'));
        $this->assertEquals('snake/case/string', Str::snakeCaseToPath('snake_case_string', false));
        $this->assertEquals('Snake/Case/String', Str::snakeCaseToUri('snake_case_string'));
        $this->assertEquals('snake/case/string', Str::snakeCaseToUrl('snake_case_string', false));

        $this->assertEquals('NamespaceString', Str::namespaceToTitleCase('Namespace\String'));
        $this->assertEquals('namespaceString', Str::namespaceToCamelCase('Namespace\String'));
        $this->assertEquals('namespace-string', Str::namespaceToKebabCase('Namespace\String'));
        $this->assertEquals('Namespace-String', Str::namespaceToDash('Namespace\String', true));
        $this->assertEquals('namespace_string', Str::namespaceToSnakeCase('Namespace\String'));
        $this->assertEquals('namespace_string', Str::namespaceToUnderscore('Namespace\String'));
        $this->assertEquals('Namespace/String', Str::namespaceToPath('Namespace\String'));
        $this->assertEquals('namespace/string', Str::namespaceToPath('Namespace\String', false));
        $this->assertEquals('Namespace/String', Str::namespaceToUrl('Namespace\String'));
        $this->assertEquals('namespace/string', Str::namespaceToUri('Namespace\String', false));

        $this->assertEquals('PathString', Str::pathToTitleCase('Path/String'));
        $this->assertEquals('pathString', Str::pathToCamelCase('/Path/String'));
        $this->assertEquals('path-string', Str::pathToKebabCase('Path/String'));
        $this->assertEquals('Path-String', Str::pathToDash('Path/String', true));
        $this->assertEquals('path_string', Str::pathToSnakeCase('Path/String'));
        $this->assertEquals('path_string', Str::pathToUnderscore('Path/String'));
        $this->assertEquals('Path\String', Str::pathToNamespace('Path/String'));

        $this->assertEquals('UrlString', Str::uriToTitleCase('/url/string'));
        $this->assertEquals('urlString', Str::urlToCamelCase('url/string'));
        $this->assertEquals('url-string', Str::uriToKebabCase('url/string'));
        $this->assertEquals('Url-String', Str::urlToDash('url/string', true));
        $this->assertEquals('url_string', Str::uriToSnakeCase('url/string'));
        $this->assertEquals('url_string', Str::urlToUnderscore('url/string'));
        $this->assertEquals('Url\String', Str::uriToNamespace('url/string'));
    }

}