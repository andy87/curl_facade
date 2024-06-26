<?php declare(strict_types=1);

/**
 * @name: knock-knock
 * @author Andrey and_y87 Kidin
 * @description Библиотека содержащая константы типов контента
 * @homepage: https://github.com/andy87/KnockKnock
 * @license CC BY-SA 4.0 http://creativecommons.org/licenses/by-sa/4.0/
 * @date 2024-05-27
 * @version 1.3.2
 */

namespace andy87\knock_knock\lib;

/**
 * Class KnockContentType
 *
 * @package andy87\knock_knock\query
 */
class ContentType
{
    public const JSON = 'application/json';
    public const XML = 'application/xml';
    public const FORM = 'application/x-www-form-urlencoded';
    public const MULTIPART = 'multipart/form-data';
    public const TEXT = 'text/plain';
    public const HTML = 'text/html';
    public const JAVASCRIPT = 'application/javascript';
    public const CSS = 'text/css';
    public const CSV = 'text/csv';
    public const PDF = 'application/pdf';
    public const ZIP = 'application/zip';
    public const GZIP = 'application/gzip';
    public const TAR = 'application/x-tar';
    public const RAR = 'application/x-rar-compressed';
    public const SEVEN_ZIP = 'application/x-7z-compressed';
    public const IMAGE = 'image/*';
    public const AUDIO = 'audio/*';
    public const VIDEO = 'video/*';
    public const FONT = 'font/*';
    public const ANY = '*/*';
}