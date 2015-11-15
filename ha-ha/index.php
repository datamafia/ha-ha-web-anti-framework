<?
/* ---------------------------------------|
 * Ha-Ha Web Anti-Framework Configuration |
 * ---------------------------------------|
 *
 * http://datamafia.com
 * @datamafia (github + twitter)
 *
 * For details on set-up and the
 * limitations of this project go
 * to the README.md file.
 *
 * MIT License
*/

// Includes
include 'config.php';
include 'Parsedown.php';

// Assembly of the assets directory
$markdown_assets_dir = $markdown_location.$assets_dir;

// Init a bucket for the creation of all files page. Used to build sitemap
$all_files_info = [];

// DRY+Laziness = $this
$br = '<br />';

// If Github
if ($external_zip_url){
    echo 'I see a github zip is defined. Let\'s see if we can do this shit'.$br;
    // name of local cached zip file
    $temp_zip = 'temp.zip';
    $temp_folder = 'temp';
    // get file name from the url
    // Pattern: https://github.com/USERNAME/REPOSITORY/archive/BRANCH.zip = "REPOSITORY-BRANCH"
    $split = explode('/', $external_zip_url);
    $sub_split = explode('.', $split[count($split)-1]);
    $markdown_location = $split[count($split)-3].'-'.$sub_split[count($sub_split)-2];
    echo 'Expected folder after successful extraction per GitHub pattern = '.$markdown_location.$br;
    $gh_zip = file_get_contents($external_zip_url);
    // delete on exist
    if(is_file($temp_zip)){
        unlink($temp_zip);
    }
    // Try to open / write
    try{
        $handle = fopen($temp_zip, 'w') or die('Cannot open file:  '.$temp_zip);
        fwrite($handle, $gh_zip);
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), $br;
        return;
    }
    // unzip
    $zip = new ZipArchive;
    if ($zip->open($temp_zip) === TRUE) {
        $zip->extractTo($temp_folder);
        $zip->close();
        echo 'Zip extraction succeeded.'.$br;

    } else {
        echo 'Zip extraction failed.'.$br;
        return;
    }
    // is the directory there?
    $markdown_location = $temp_folder.'/'.$markdown_location;
    if(!is_dir($markdown_location)){
        echo 'Expected GitHub folder missing. '.$br;
        return;
    }
    // is there an assets folder?
    if(isset($assets_dir)){
        $markdown_assets_dir = $markdown_location.'/'.$assets_dir;
        echo 'Looking for Github assets folder.'.$br;
        $markdown_assets_dir = $markdown_location.'/'.$assets_dir;
        if (!is_dir($markdown_assets_dir)){
            echo'Could not find expected markdown assets folder '.$markdown_assets_dir.'.'.$br;
            return;
        }else{
            echo 'Success, found Github assets folder.'.$br;
        }
    }else{
        // no assets, that is okay
        $markdown_assets_dir = False;
    }

}else{
    echo 'Github not set, going local.'.$br;
}

// Glob all the MD files
$md_files = array_filter(glob($markdown_location.'*'), 'is_file');

// validate file presence
if (count($md_files) < 1){
    echo 'No md files found.'.$br;
    return;
}
echo 'Original file count: '.count($md_files).$br;

// set up for markdown conversion
$Parsedown = new Parsedown();

// KISS: header, nav, footer
$header_loc = 'includes/header.php';
$nav_loc = 'includes/nav.php';
$footer_loc = 'includes/footer.php';

$handle = fopen($header_loc, "r") or die("Unable to open file!");
$header =  fread($handle,filesize($header_loc));
fclose($handle);

$handle = fopen($nav_loc, "r") or die("Unable to open file!");
$nav =  fread($handle,filesize($nav_loc));
fclose($handle);

$handle = fopen($footer_loc, "r") or die("Unable to open file!");
$footer =  fread($handle,filesize($footer_loc));
fclose($handle);

// Clean up file system

// clear *.html files
$htmls = array_filter(glob($html_folder."/*.html"), 'is_file');
if (count($htmls)>0){
    foreach($htmls as $html){
        $splode = explode('/', $html);
        echo 'Deleting: '.$splode[count($splode)-1].$br;
        unlink($html_folder.$splode[count($splode)-1]);
    }
}

// Work with MD files
$created_file_count = 0;
// loop on markdown files
foreach($md_files as $file){
    // get file ext
    $split = explode('.', $file);
    // check allowed file ext
    if (!in_array($split[count($split)-1], $allowed_file_ext)){
        printf('File ext not allowed, skipping %s %s', $file, $br);
        continue;
    }
    // Build new file name. Period file naming really not supported well. Better to avoid
    $split2 = explode('/', $split[count($split)-2]);
    $new_file_name = $split2[count($split2)-1].'.html';
    // Read file
    $handle = fopen($file, "r") or die("Unable to open file!");
    if (filesize($file)<1){
        echo 'Skipping '.$file.' because the file size was zero.'.$br;
        continue;
    }
    $var =  fread($handle,filesize($file));
    fclose($handle);

    // wrap html w/header/footer
    $parsed_to_html = $Parsedown->text($var);
    $parsed_to_html = $header.$nav.$parsed_to_html.$footer;
    file_put_contents($html_folder.$new_file_name, $parsed_to_html);
    echo 'Just created <a title="Opens in new window" href="/'
        .$new_file_name.'" target="_blank">'.$new_file_name.'</a> from '
        .$file.'.'.$br;
    $all_files_info[]['filename'] = $new_file_name;
    $created_file_count += 1;
}

// Build all files
//var_dump($all_files_info);
if (count($all_files_info)>0){
    $html_links = '';
    foreach($all_files_info as $info){
        $html_links .= '<li>';
        $html_links .= '<a href="'.$html_folder.$info['filename'].'">'.$info['filename'].'</a>';
        $html_links .= '</li>';
    }
    $html_links = '<ul id="all_files">'.$html_links.'</ul>';
}

$html_links = $header.$nav.$html_links.$footer;
file_put_contents($html_folder.$sitemap, $html_links);
echo 'Just created <a title="Opens in new window" href="/'
    .$sitemap.'" target="_blank">'.$sitemap.'</a> from '.$br;

$assets_dir = $html_folder.$assets_dir;

// Handle images, clean and moves.
if ($assets_dir && !is_dir($assets_dir)){
    mkdir($assets_dir, 0700);
}elseif($assets_dir){
    // remove contents, should not be nested folders
    $assets = glob($assets_dir.'/{,.}*', GLOB_BRACE); // grabs pesky (dot) .files
    if(count($assets)>0){
        foreach($assets as $asset){
            if($asset == $assets_dir.'/.' || $asset == $assets_dir.'/..'){
                continue; // file system artifact from glob
            }
            try {
                unlink($asset);
            }catch(Exception $e){ // permission error
                echo $e;
                return;
            }
        }
    }
    rmdir($assets_dir);
}
// copy assets
if($markdown_assets_dir && is_dir($markdown_assets_dir)){
    xcopy($markdown_assets_dir, $assets_dir);
}else{
    echo 'Warning: $markdown_assets_dir ('.$markdown_assets_dir.' might be a problem.)'.$br;
}
$created_file_count = $created_file_count+1; // the all files listing
echo 'Done, created '.$created_file_count.' files (includes the all files listing)'.$br;

// One more lib I didn't want to write.
/**
 * Copy a file, or recursively copy a folder and its contents
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       string   $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry", $permissions);
    }

    // Clean up
    $dir->close();
    return true;
}

