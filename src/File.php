<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils file helper class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
class File
{

    /**
     * Basename (filename.ext)
     * @var ?string
     */
    protected ?string $basename = null;

    /**
     * Filename (filename)
     * @var ?string
     */
    protected ?string $filename = null;

    /**
     * Extension (.ext)
     * @var ?string
     */
    protected ?string $extension = null;

    /**
     * Path (/some/path)
     * @var ?string
     */
    protected ?string $path = null;

    /**
     * Size
     * @var int
     */
    protected int $size = 0;

    /**
     * Mime type
     * @var ?string
     */
    protected ?string $mimeType = null;

    /**
     * Mime types
     * @var array
     */
    protected static array $mimeTypes = [
        'avi'   => 'video/x-msvideo',
        'bin'   => 'application/octet-stream',
        'bmp'   => 'image/bmp',
        'bz'    => 'application/x-bzip',
        'bz2'   => 'application/x-bzip2',
        'css'   => 'text/css',
        'csv'   => 'text/csv',
        'doc'   => 'application/msword',
        'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'gz'    => 'application/gzip',
        'gif'   => 'image/gif',
        'htm'   => 'text/html',
        'html'  => 'text/html',
        'ico'   => 'image/vnd.microsoft.icon',
        'ics'   => 'text/calendar',
        'jar'   => 'application/java-archive',
        'jpe'   => 'image/jpeg',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'js'    => 'text/javascript',
        'json'  => 'application/json',
        'jwt'   => 'application/jwt',
        'log'   => 'text/plain',
        'mid'   => 'audio/midi',
        'midi'  => 'audio/midi',
        'mp3'   => 'audio/mpeg',
        'mp4'   => 'video/mp4',
        'mpeg'  => 'video/mpeg',
        'odp'   => 'application/vnd.oasis.opendocument.presentation',
        'ods'   => 'application/vnd.oasis.opendocument.spreadsheet',
        'odt'   => 'application/vnd.oasis.opendocument.text',
        'oga'   => 'audio/ogg',
        'ogv'   => 'video/ogg',
        'ogx'   => 'application/ogg',
        'otf'   => 'font/otf',
        'png'   => 'image/png',
        'pdf'   => 'application/pdf',
        'php'   => 'application/x-httpd-php',
        'ppt'   => 'application/vnd.ms-powerpoint',
        'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'psd'   => 'image/vnd.adobe.photoshop',
        'rar'   => 'application/vnd.rar',
        'rtf'   => 'application/rtf',
        'sgml'  => 'application/sgml',
        'sql'   => 'application/sql',
        'svg'   => 'image/svg+xml',
        'tar'   => 'application/x-tar',
        'tif'   => 'image/tiff',
        'tiff'  => 'image/tiff',
        'ttf'   => 'font/ttf',
        'tsv'   => 'text/tsv',
        'txt'   => 'text/plain',
        'wav'   => 'audio/wav',
        'xhtml' => 'application/xhtml+xml',
        'xls'   => 'application/vnd.ms-excel',
        'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xml'   => 'application/xml',
        'yaml'  => 'application/yaml',
        'zip'   => 'application/zip',
    ];

    /**
     * Default mime types
     * @var string
     */
    protected static string $defaultMimeType = 'application/octet-stream';

    /**
     * Constructor
     *
     * Instantiate the file object
     *
     * @param  ?string $filename
     */
    public function __construct(?string $filename = null)
    {
        $info = pathinfo($filename);

        if (!empty($info['basename'])) {
            $this->setBasename($info['basename']);
        }
        if (!empty($info['filename'])) {
            $this->setFilename($info['filename']);
        }
        if (!empty($info['dirname'])) {
            $this->setPath($info['dirname']);
        }
        if (!empty($info['extension'])) {
            $this->setExtension($info['extension']);
        }

        if (file_exists($filename)) {
            $this->setSize(filesize($filename));
        }
    }

    /**
     * Get common mime types
     *
     * @return array
     */
    public static function getMimeTypes(): array
    {
        return (new self())->getAllMimeTypes();
    }

    /**
     * Get file's mime type
     *
     * @param  string $filename
     * @return ?string
     */
    public static function getFileMimeType(string $filename): ?string
    {
        return (new self($filename))->getMimeType();
    }

    /**
     * Set the basename
     *
     * @param  string $basename
     * @return File
     */
    public function setBasename(string $basename): File
    {
        $this->basename = $basename;
        return $this;
    }

    /**
     * Get the basename
     *
     * @return ?string
     */
    public function getBasename(): ?string
    {
        return $this->basename;
    }

    /**
     * Has the basename
     *
     * @return bool
     */
    public function hasBasename(): bool
    {
        return ($this->basename !== null);
    }

    /**
     * Set the filename
     *
     * @param  string $filename
     * @return File
     */
    public function setFilename(string $filename): File
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Get the filename
     *
     * @return ?string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Has the filename
     *
     * @return bool
     */
    public function hasFilename(): bool
    {
        return ($this->filename !== null);
    }

    /**
     * Set the extension
     *
     * @param  string $extension
     * @return File
     */
    public function setExtension(string $extension): File
    {
        $this->extension = $extension;
        if (array_key_exists(strtolower($extension), self::$mimeTypes)) {
            $this->setMimeType(self::$mimeTypes[strtolower($extension)]);
        } else {
            $this->setDefaultMimeType();
        }
        return $this;
    }

    /**
     * Get the extension
     *
     * @return ?string
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * Has the extension
     *
     * @return bool
     */
    public function hasExtension(): bool
    {
        return ($this->extension !== null);
    }

    /**
     * Set the path
     *
     * @param  string $path
     * @return File
     */
    public function setPath(string $path): File
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get the path
     *
     * @return ?string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Has the path
     *
     * @return bool
     */
    public function hasPath(): bool
    {
        return ($this->path !== null);
    }

    /**
     * Set the size
     *
     * @param  int $size
     * @return File
     */
    public function setSize(int $size): File
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Get the size
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Has the size
     *
     * @return bool
     */
    public function hasSize(): bool
    {
        return ($this->size > 0);
    }

    /**
     * Set the mime type
     *
     * @param  string $mimeType
     * @return File
     */
    public function setMimeType(string $mimeType): File
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Get the mime type
     *
     * @return ?string
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * Has the mime type
     *
     * @return bool
     */
    public function hasMimeType(): bool
    {
        return ($this->mimeType !== null);
    }

    /**
     * Set the mime type to default mime type
     *
     * @return File
     */
    public function setDefaultMimeType(): File
    {
        $this->mimeType = self::$defaultMimeType;
        return $this;
    }

    /**
     * Get the default mime type
     *
     * @return string
     */
    public function getDefaultMimeType(): string
    {
        return self::$defaultMimeType;
    }

    /**
     * Set the default mime type
     *
     * @return bool
     */
    public function isDefaultMimeType(): bool
    {
        return ($this->mimeType == self::$defaultMimeType);
    }

    /**
     * Get the all common mime types
     *
     * @return array
     */
    public function getAllMimeTypes(): array
    {
        return self::$mimeTypes;
    }

    /**
     * Does the file exist
     *
     * @return bool
     */
    public function exists(): bool
    {
        $fullPath = ($this->hasPath()) ? $this->path . DIRECTORY_SEPARATOR . $this->basename : $this->basename;
        return file_exists($fullPath);
    }

    /**
     * Get the file contents
     *
     * @return mixed
     */
    public function getContents(): mixed
    {
        $fullPath = ($this->hasPath()) ? $this->path . DIRECTORY_SEPARATOR . $this->basename : $this->basename;
        return file_get_contents($fullPath);
    }

    /**
     * Convert file to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'basename'  => $this->basename,
            'filename'  => $this->filename,
            'extension' => $this->extension,
            'path'      => $this->path,
            'size'      => $this->size,
            'mime_type' => $this->mimeType,
        ];
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString(): string
    {
        return ($this->hasPath()) ? $this->path . DIRECTORY_SEPARATOR . $this->basename : $this->basename;
    }

}