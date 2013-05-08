Israblog Import Plugin 1.4
==========================

================================================================================
Copyright
================================================================================
  This plugin is copyright (C) 2006 by Shachar Shemesh

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.
 
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License along
   with this program; if not, write to the Free Software Foundation, Inc.,
   51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

================================================================================
Installation & Use Instructions
================================================================================
  Place the file "israblog.php" inside your wp-admin/import directory. If you
  want a translation, place the relevant translation file (israblog-he_IL.mo
  for Hebrew) in the same directory.

  Go to your Israblog account, and ask to "back up your blog". Choose whatever
  date range seems apropriate to you. Ask for a backup with comments and save
  the result. If you are using Firefox you can choose whether to use HTML
  format and use the "Save Page" option, or use the Word format. If you are
  using Internet Explorer, however, only use the "Word" format. Do NOT try to
  use the HTML format, as IE changes the page when it saves it in a way that
  the import cannot handle.

  In wordpress, go to your administration account. Select "Import" and then
  "Israblog". On the "Browse" button, give the software the path to your saved
  backup file.

  You should see the post titles appear on screen as the HTML file is being
  processed.

================================================================================
IMPORTANT NOTE!!
================================================================================
Internet Explorer (at least version 6) does not have any option to save the page
in the same way it is received from the server. If you manage to do
"View/Source" and then save the result, that may work. Otherwise, just use
Word format, or use firefox to get the backup page.

================================================================================
What Does and Doesn't Get Processed
================================================================================
  This plugin imports the posts and the comments. The comment nesting is also
  being recorded in the database (wordpress 2's "comment_parent" column), but is
  not being displayed by wordpress in its default configuration. The usual
  nested comments plugin (Brian's Threaded Comments) uses "comment_reply_ID".
  In order to get comments nested after import using Brian's plugin you will
  need to run the following command on the database:
  update wp_comments set comment_reply_ID = comment_parent;

  Comment mood is not being processed, mostly because wordpress has no
  corresponding mechanism.

  Posts categories are not being processed because they do not appear in the
  HTML israblog produces.

================================================================================
Known Bugs
================================================================================
  The posts GUID is not being filled in. In layman terms, this means that
  running the plugin a second time will result in duplicate posts.

  Connecting directly to the site and yanking the data from the HTML woulld, in
  retrospect, probably have been more effective, made parsing SIMPLER, while not
  being more difficult. Hind sight is always 20/20.

  The program uses the "date_default_timezone_set" function in order to make
  sure that Israblog's timestamps are correctly interpreted. This function is
  only available for PHP version 5.1 and higher. People running earlier versions
  should make sure that the server's time zone is set to 'Asia/Jerusalem'.
  Failing to do so will result in misinterpreted timestamps.

  No translation is done to the HTML content of posts and comments. As a result,
  smiley icons and inward pointing links are broken after transition.

================================================================================
Debugging
================================================================================
  The HTML parser is a state machine implemented as part of the "get_posts"
  method. If the parsing goes wrong, tracking which line and which state the
  machine is on can help determine what the problem is.

  In order to switch on said output, load the main form (where you get a chance
  to choose the file for upload), add "&debug" to the end of the URL, and load
  the page at its new address. When parsing the file, lines of the form:
  "Line 2916, state 3" will be printed. The code has comments stating what each
  state should mean.

  Please note that if the state machine always stays at state "0", your file is
  not being correctly parsed.

================================================================================
Changelog
================================================================================
Version 1.4
  - Update the docs to reflect that Word format works as well (it's actually
    the same HTML file inside, but avoids the nasty IE save problem).
  - The plugin is now fully translated into Hebrew.
Version 1.3
  - Fix some incorrect format for reference passing (only problem on some PHP
    installations).
  - Detect backup files generated by IE6 (and thus - changed beyond our ability
    to parse them).
Version 1.2
  - Only run "date_default_timezone_set" if it's PHP 5.1 or higher. Leave
    earlier PHP versions as is and hope that the server's time zone is set to
    Israel.
  - Adapt for the (lack of) constants support of PHP 4
  - The importer now fully works on PHP 4 as well
Version 1.1
  - Switch from using custom regexp for escaping strings to using the standard
    escape function
  - Detect end of uninteresting header automatically, not based on line number.
  - Make sure that the file is deleted in case of error.
  - Comment author needs to be escaped as well - fixed
  - Add "debug" switch
  - Better handling of end of file
Version 1.0
  - Initial release

================================================================================
Author Information
================================================================================
  The Israblog import module, as well as this readme, were written by Shachar
  Shemesh. I can be contacted at israblog-import at shemesh dot biz.
