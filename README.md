# ThemiSQL
A PHP-based form generator for SQL

ThemiSQL is a revolutionary new approach for the development of PHP applications, which relys on SQL. Considering the classic MVC architecture, the software generates solely on the basis of the table information appropriate forms - without the need of a "handcrafted" View or Controller. In addition to the broad support of various DBMS - they need only a driver for PDO - also foreign keys are not a problem. The various independent views also offer a huge scope for extensions and modifications of the overall system.

Prerequisites:
- Web server with PHP 5.4+
- Existing PDO driver for the DBMS
- "mod_rewrite" for user-friendly URLs (BEST)

Features:
- Fully dynamic form creation
- Easy installation & maintenance (-> completely file-based)
- Provision of independent forms using different views
- Simple and powerful configurability
 * custom design via CSS
 * custom SQL commands, custom representation of SQL data types, ...
 * custom appereance (i.e. translations)
 * custom Pre-authentication (i.e. survey systems)
 
Installation:
- Upload the files
- Create a new view in the directory "Config"
	1. Create a directory with the name of the view
	2. Create a separate "config.json" which overrides the base configuration of the root directory
- Done! (Call the new view via "website.tld/index.php?view=NAME" or "website.tld/NAME" (with "mod_rewrite"))