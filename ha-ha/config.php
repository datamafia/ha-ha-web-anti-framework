<?
/* ----------------------------------|
 * Ha-Ha Web Framework Configuration |
 * ----------------------------------|
 *
 * by @datamafia (GitHub|Twitter)
*/


/* -------------------|
 * SITE CONFIGURATION |
 * -------------------|
 *
 * Settings for the final site presentation and paths.
*/


/* Destination  of HTML relative to this script
 *
 * Trailing slash required.
*/
$html_folder = '../';

/* Location of the assets.
 *
 * This is the folder where "stuff like images goes".
 *
 * The name matches the folder name contained in the Markdown material.
 *
 * For example, consider this file structure:
 *
 * /{markdown folder}
 *      /{$assets_dir}/{stuff like images}
 *
 * should match this final file structure on site:
 *
 * /{html files}
 * /{$assets_dir}/{stuff like images}
 *
 * Please, no nested folders, follow the example.
 *
 * No limitations placed on content type.
 *
 * No leading ro trailing slash
*/
$assets_dir = 'assets'; // set to False if there are no assets (no images, nuthin')


/* -------------------|
 * LOCAL MARKDOWN USE |
 * -------------------|
 *
 * Note: $github_zip_url must be set to False!!!
*/

/* Location of the master folder (md files and assets) relative to this script on the file system
 *
 * Change as needed.
 *
 * Trailing slash required
*/
$markdown_location= '../test-data/';

/* Name of the sitemap file
 *
 * Change as needed, but required.
*/
$sitemap = 'sitemap.html';


/* ------------------------|
 * GITHUB OR EXTERNAL FILE |
 * ------------------------|
 *
 * Github is easy and Ha-Ha consumes zip files. They get along great.
 *
 * Note: If $github_zip_url is set local markdown will not be parsed!
 *
 * @todo $github_zip_url set to branch of test data
 *
 * Does not need to be github, only a zip file that unpacks correctly.
 *
 * GitHub file name pattern: https://github.com/{user}/{repo}/archive/{branch name}.zip
 *
 * Private repositories are not supported. That is a lot of code, there are ways to work round this
 * limitation (local Git, pull, etc - beyond the scope of this project).
 *
 * FQDN required. LAN, intranet use will be tolerated, Windows might be problematic (send feedback please).
*/
$external_zip_url = False;

// Uncomment next line to test a git based version of this site.
//$external_zip_url = 'https://github.com/datamafia/ha-ha-web-anti-framework/archive/remote-test-data.zip';


/* --------------------|
 * GLOBAL MARKDOWN USE |
 * --------------------|
 *
 * Accepted fie extensions, add/remove entries as needed.
*/
$allowed_file_ext = [];
$allowed_file_ext[] = 'md';
$allowed_file_ext[] = 'markdown';
