<?php
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
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
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    2.3.0
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
        'aiff'  => 'audio/aiff',
        'avi'   => 'video/x-msvideo',
        'bin'   => 'application/octet-stream',
        'bmp'   => 'image/bmp',
        'bz'    => 'application/x-bzip',
        'bz2'   => 'application/x-bzip2',
        'css'   => 'text/css',
        'csv'   => 'text/csv',
        'doc'   => 'application/msword',
        'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'flac'  => 'audio/flac',
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
        'm4a'   => 'audio/mp4',
        'mp4'   => 'video/mp4',
        'mov'   => 'video/quicktime',
        'mpeg'  => 'video/mpeg',
        'odp'   => 'application/vnd.oasis.opendocument.presentation',
        'ods'   => 'application/vnd.oasis.opendocument.spreadsheet',
        'odt'   => 'application/vnd.oasis.opendocument.text',
        'ogg'   => 'audio/ogg',
        'oga'   => 'audio/oga',
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
        'wmv'   => 'video/x-ms-wmv',
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
        $info = [];

        if (!empty($filename)) {
            $info = pathinfo($filename);
            if (file_exists($filename)) {
                $this->setSize(filesize($filename));
            }
        }

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
    }

    /**
     * Format file size
     *
     * @param  int    $filesize
     * @param  int    $round
     * @param  ?bool  $case null = UPPER (MB); true = Title (Mb); false = lower (mb)
     * @param  string $space
     * @return string
     */
    public static function formatFileSize(int $filesize, int $round = 2, ?bool $case = null, string $space = ' '): string
    {
        $file = new self();
        $file->setSize($filesize);
        return $file->formatSize();
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
     * Check if file is an image file
     *
     * @param  string $filename
     * @return bool
     */
    public static function isImage(string $filename): bool
    {
        $imageFormats = [
            static::$mimeTypes['bmp'],
            static::$mimeTypes['gif'],
            static::$mimeTypes['ico'],
            static::$mimeTypes['jpe'],
            static::$mimeTypes['jpg'],
            static::$mimeTypes['jpeg'],
            static::$mimeTypes['png'],
            static::$mimeTypes['psd'],
            static::$mimeTypes['svg'],
            static::$mimeTypes['tif'],
            static::$mimeTypes['tiff'],
        ];

        return in_array(static::getFileMimeType($filename), $imageFormats);
    }

    /**
     * Check if file is a web image file
     *
     * @param  string $filename
     * @return bool
     */
    public static function isWebImage(string $filename): bool
    {
        $webImageFormats = [
            static::$mimeTypes['gif'],
            static::$mimeTypes['ico'],
            static::$mimeTypes['jpe'],
            static::$mimeTypes['jpg'],
            static::$mimeTypes['jpeg'],
            static::$mimeTypes['png'],
            static::$mimeTypes['svg'],
        ];

        return in_array(static::getFileMimeType($filename), $webImageFormats);
    }

    /**
     * Check if file is a video file
     *
     * @param  string $filename
     * @return bool
     */
    public static function isVideo(string $filename): bool
    {
        $videoFormats = [
            static::$mimeTypes['avi'],
            static::$mimeTypes['mov'],
            static::$mimeTypes['mp4'],
            static::$mimeTypes['mpeg'],
            static::$mimeTypes['ogv'],
            static::$mimeTypes['ogx'],
            static::$mimeTypes['wmv'],
        ];

        return in_array(static::getFileMimeType($filename), $videoFormats);
    }

    /**
     * Check if file is an audio file
     *
     * @param  string $filename
     * @return bool
     */
    public static function isAudio(string $filename): bool
    {
        $audioFormats = [
            static::$mimeTypes['aiff'],
            static::$mimeTypes['flac'],
            static::$mimeTypes['mid'],
            static::$mimeTypes['midi'],
            static::$mimeTypes['mp3'],
            static::$mimeTypes['m4a'],
            static::$mimeTypes['ogg'],
            static::$mimeTypes['oga'],
            static::$mimeTypes['ogx'],
            static::$mimeTypes['wav'],
        ];

        return in_array(static::getFileMimeType($filename), $audioFormats);
    }

    /**
     * Check if file is a text file
     *
     * @param  string $filename
     * @return bool
     */
    public static function isText(string $filename): bool
    {
        $textFormats = [
            static::$mimeTypes['csv'],
            static::$mimeTypes['log'],
            static::$mimeTypes['tsv'],
            static::$mimeTypes['txt'],
        ];

        return in_array(static::getFileMimeType($filename), $textFormats);
    }

    /**
     * Check if file is a compressed file
     *
     * @param  string $filename
     * @return bool
     */
    public static function isCompressed(string $filename): bool
    {
        $compressedFormats = [
            static::$mimeTypes['bz'],
            static::$mimeTypes['bz2'],
            static::$mimeTypes['gz'],
            static::$mimeTypes['jar'],
            static::$mimeTypes['rar'],
            static::$mimeTypes['tar'],
            static::$mimeTypes['zip'],
        ];

        return in_array(static::getFileMimeType($filename), $compressedFormats);
    }

    /**
     * Check if file is a Word document
     *
     * @param  string $filename
     * @return bool
     */
    public static function isWord(string $filename): bool
    {
        $wordFormats = [
            static::$mimeTypes['doc'],
            static::$mimeTypes['docx'],
            static::$mimeTypes['rtf'],
        ];

        return in_array(static::getFileMimeType($filename), $wordFormats);
    }

    /**
     * Check if file is a PDF document
     *
     * @param  string $filename
     * @return bool
     */
    public static function isPdf(string $filename): bool
    {
        return (static::getFileMimeType($filename) == 'application/pdf');
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
     * Format size into human-readable string
     *
     * @param  int    $round
     * @param  ?bool  $case null = UPPER (MB); true = Title (Mb); false = lower (mb)
     * @param  string $space
     * @return string
     */
    public function formatSize(int $round = 2, ?bool $case = null, string $space = ' '): string
    {
        $prefix = '';
        $byte   = ($case !== null) ? 'b' : 'B';

        if ($this->size >= 1000000000000) {
            $prefix    = ($case !== false) ? 'T' : 't';
            $formatted = round($this->size / 1000000000000, $round);
        } else if (($this->size < 1000000000000) && ($this->size >= 1000000000)) {
            $prefix    = ($case !== false) ? 'G' : 'g';
            $formatted = round($this->size / 1000000000, $round);
        } else if (($this->size < 1000000000) && ($this->size >= 1000000)) {
            $prefix    = ($case !== false) ? 'M' : 'm';
            $formatted = round($this->size / 1000000, $round);
        } else if (($this->size < 1000000) && ($this->size >= 1000)) {
            $prefix    = ($case !== false) ? 'K' : 'k';
            $formatted = round($this->size / 1000, $round);
        } else {
            $formatted = $this->size;
        }

        return $formatted . $space . $prefix . $byte;
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
