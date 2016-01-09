# Ha-Ha Anti-Framework

<img id="haha" src="assets/ha-ha.jpg" title="I am actually not a huge Simpson's fan."/>

<hr />

I wrote an Anti-Framework called ```Ha-Ha```. Nothing more than a simple static site generator using markdown with Github integration (zip file baby!), local parsing, or an external target. For better or worse written in PHP.

<hr />

##Features:

* __For those who love [Markdown](https://daringfireball.net/projects/markdown/)__.
* Drop dead simple, it's kinda like a joke, but works.
* Sitemap generation free with purchase&#8482;.
* Wrap the site in whatever HTML, CSS, or javascript you may want (workable example included)
* Easy configuration.
* Demo should be running in minutes.
* Use GitHub (zip file), <a href="#gist" title="More info on Gist use">Gists (secret or public see</a>), local files, or point to a zip file somewhere else.
* No database, simple, static, and loved.
* PHP only used to render HTML files and can be easily concealed or secured.
* Not object orientated, not even functional, but __procedural!__
* About 200 lines of code _(and a little tiny lib, a config file, and a function from the interweb)_.
* Mobile ready design, special thanks to [initializr](http://www.initializr.com/) (HTML5BP) #lazy.
* Uses [Parsedown](http://parsedown.org) by [Emanuil Rusev](http://erusev.com) ([GitHub](https://github.com/erusev/parsedown)). #thx
* A handy [function](http://aidanlister.com/2004/04/recursively-copying-directories-in-php/) from 2004  via Aidan Lister #thx


## Installation

1. Pull, copy, or download [```Ha-Ha``` Anti-Framework](https://github.com/datamafia/ha-ha-web-anti-framework/).
1. Install locally or on your server (root).
1. Refresh ```yoursite.com/ha-ha``` to build the site
1. Done - now configure for your needs!

## Configuration

__Important__: Read and configure ```config.php``` (```/ha-ha/config.php```). To set the site up very fast, place the ```/ha-ha/``` folder on the root and everything will make sense.

>_Including gravity, but you must open your mind to the possibility that gravity sucks._&#8482;

In the ```config.php``` file there is a line where the variable ```$external_zip_url``` is commented out but points to a GitHub repo+branch for testing data. Feel free to toggle that on/off as needed to test. The site will render a different version.

###Optionally, but actually necessary...

Configure the header, footer and nav (html). These files are located in the ```ha-ha/includes``` folder as:

* ```header.php```: The header, including the ```head```, ```html```, and any other tags you may need to open.
* ```nav.php```: A navigation element, placed directly after the header.
* ```index.php```: A file added keep the directory private.
* ```footer.php```: The footer, bottom of the page, add any script includes or closing tags (like the ```html``` or ```body``` tags)

__Note__: _The above structure is not carved in stone&#8482;, with minimal PHP experience any user can work with the pattern put forth in ```Ha-Ha``` and reach new heights._

It is also __highly recommended__ to change the ```ha-ha``` directory name to something private. No need to let the robots or others touching the site trigger html generation unnecessarily. 

>If you _really want to go nuts_ drop some ```bash``` scripting and go weird moving this off the web path. Please tell me how it worked!


## How it works (10,000ft version)

1. Write in Markdown.
1. Use an "assets" folder for image, downloads, etcetera that is at the same directory level as the Markdown files. 
 * No nested folders.
 * Totally [K.I.S.S.](https://en.wikipedia.org/wiki/KISS_principle) people!
1. Put locally on the file system, or in Github, or point to a remote zip file.
1. Run ```website.com/ha-ha``` to generate the site.

When ```website.com/ha-ha``` is run a text dialog is returns showing the status, errors (if any) and all of the pages created, including the sitemap.

All markdown files will be turned (magically) into html files. So ```page.md``` will have a ```page.html``` analog on your website.


## How it works (20ft version) : GitHub

Using a simple file structure pattern of ```containing-folder``` with an ```assets``` folder both markdown and assets are converted into html and placed on a running server.

All examples operate under the assumption that the markdown will be on the root level of the site (website.com). The example markdown files (local and remote) look like this:

```
/test-data
	/assets
		ha-ha.jpg
	contact.md
	index.md
	README.md
```

The ```test-data``` is the ```containing-folder``` and ```assets``` folder contains the assets.

By running (refreshing) ```website.com/ha-ha``` all of the data in ```test-data``` is parsed into html, placed at the site root level and the assets (folder) is copied with all of its contents. 

__No nested folders!__

The resulting website would contain (starting at ```$_SERVER['DOCUMENT_ROOT']```):

```
/ha-ha
	...the anti-framework core
/assets
	ha-ha.jpg
index.html
contact.html
README.html
sitemap.html
```
```README.html``` is along for the ride to provide consistency between local and remote (GitHub) testing. _Actually, ```README.md``` is generated by ```bash.sh``` running a simple shell ``cp`` command.

```sitemap.html``` is an operation added that builds a page listing all of the pages created.

__You must have ```$gist_zip_url``` and ```$github_agent``` to False!__

## How it works (20ft version) : Gist <a name="gist"></a>

Minor differences using a Gist as compared to GitHub.

Gists support revisions, the ```Ha-Ha``` Anti-Framework automagically uses the most current revision. Where GitHub has the availability of a ```master``` zip file that is tied to the branch (usually master) there is no equivalent for Gists (correct?). To compensate an API call is made to the GitHub API. Via this call a list of revisions are available, and from that data a download target can be identified.

To __configure__ Gist use, there are 2 parameters to set in the ```index.php``` file:

* ```$gist_zip_url```: This is the location of (sharable link for) the gist. 
 * The format is <code>https://gist.github.com/</code> ```username``` <code> /</code> ```gist-id``` <code>/</code>
* ```$github_agent```: GitHib [asks](https://developer.github.com/v3/#user-agent-required) that a user agent is sent with the API call used to determine the most current version.
* The path to the MD files is slightly different as well, this is handled.

__You must have ```$external_zip_url``` to False!__

### Philosophical Operation

The guiding principle of Ha-Ha is to be able to write markdown files, reference assets (locally) and know that things will be an HTML analog on the web.

The allows an image to be added in markdown (using straight HTML in this example) as:

```
<img src="assets/ha-ha.jpg" alt="Hi"/>
```

By generating the site the path of ```assets/ha-ha.jpg``` (no leading slash) will function.

Additionally, any file in markdown will have a html analog. The file ```foo.md``` will be ```foo.html``` on the site. Internal linking of pages and content is pretty easy (but you do need to understand HTML a bit).

### Collateral Operation

Each time ```website.com/ha-ha``` is run the anti-framework will:

* Delete and recreate the assets folder on the server.
 * This will __not__ delete the assets folder contained with the md files.
 * The assets folder ```$html_folder``` + ```$assets_dir``` is effected.
* Erase any ```*.html``` files on the (correct) level of the server.
* Possibly download, unzip, and manipulate foreign files when the ```$external_zip_url``` is not ```False```

So, __don't add additional html files__ to the directory where Ha-Ha will be working. They will get erased.

Also, __don't name anything that would generate a collision__. By default the Ha-Ha Anti-framework uses ```assets``` and this would erase any existing folder of the same name on server. These collisions can easily be handled in the configuration file.

## Upgrading

```
Replace the ```index.php``` file in ```/ha-ha/``` unless otherwise directed.
```

## FAQs

* __Why PHP?__
* First, I love Python, it is great. Much better than PHP (#imho). But Python is a bitch to set up on the web for a simple little markdown driven site. I have written this same basic application in Python a few times, so much (solid) code, but very complex. PHP by contrast is the most common, easiest to work with, and very available on all servers. I am interested in supporting a project I need, use, and is easy for others to take advantage of for their web needs. In that vein, PHP is a good choice.
<hr />
* __How about permission errors?__
* That is on you. Most shared servers should support the read, write, etc used. If not, sniff around and see what you can find out. Feel free to drop me a note or put in a (well documented) [issue](https://github.com/datamafia/ha-ha-web-anti-framework/issues).
<hr>

* __Did you say procedural?__
* Yes. Why not? I remember one start-up mitigating (temporarily but ultimately without success) DB race conditions by using procedural code to run transactions faster. That code was foolish, but OO is often abusive and unnecessary. As I do more ```*-ops``` and need to write complex shell scripts I am taken back to the old days of procedural code (when I was a kid in the 1980's).
* For the record Emanuil Rusev's work (Parsedown lib) is OO. But I didn't write it, I only implemented it as my Markdown to HTML engine. Seems like good work, thanks. One function is from Aidan Lister.

<hr />

* __What's with the name?__

* In needing a simple solution to put Markdown files up on the web, I started writing this code. At about 50 lines of code it worked as a good MVP. All I heard in my head was "ha ha" a'la Nelson from the Simpsons.

* The "Ha Ha" is a reaction to not needing a database, using simple basic tools, and downright simple no bullshit "get a site running fast" ideology.

> "Ha Ha" --Nelson

<hr />
* __Why not JS?__
* Go away, now.

#License

[MIT style](https://opensource.org/licenses/MIT).

```
Copyright (c) <2015-2016> <Data Mafia>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
```
