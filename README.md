<http://grepbook.co.uk>
=======================

Installation Notes
------------------

You will need to create a "files" directory, writable by the www-data user. 
This directory should not be browsable to preserve privacy. You can acheive
the latter with `Options -Indexes` in an .htaccess file.

Known Issues
------------

* Posts older than mid Sept 2008 with seven digit IDs (as apposed to twelve) 
  return "This content is currently unavailable" at facebook.com. See
  <http://facebook.stackoverflow.com/questions/9018463/access-old-posts-with-seven-digit-ids/9018545>
