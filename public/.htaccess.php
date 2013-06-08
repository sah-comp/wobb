# S .htaccess file.
#
# If you are able to change your webservers configuration it would be
# better to define all the things in your apache configuration instead
# of using this .htaccess file.

# Charset defaults to utf-8
AddDefaultCharset utf-8

# utf-8 charset for common filetypes
AddCharset utf-8 .html .css .js .xml .json .rss

#
# Use index.php as directory index and index.html as second choice
#
DirectoryIndex index.php index.html

#
# Pass everything through index.php with flight
#
RewriteEngine On
RewriteBase /x/public/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
