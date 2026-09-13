# Reset Course Badges

Reset Course Badges is a local plugin for Moodle 5.2 and later. After a course reset or restore finishes, it can automatically remove outdated badge definitions belonging to that reused course while preserving users' issued badges.

## Safety rules

* Issued badge definitions are archived so users keep their awards.
* Site badges are never touched.
* Badges in other courses are never touched.
* The automation runs after both course reset and course restore operations.
* The automation can be disabled in Site administration > Plugins > Local plugins > Reset Course Badges.

## Installation

Install `local_resetcoursebadges.zip` from Site administration > Plugins > Install plugins, then complete the Moodle database upgrade.

## Important behavior

The plugin runs only after Moodle reports that a course reset or restore has ended. Zero-recipient badge definitions are permanently deleted. Definitions with issued records are archived so Moodle keeps the awards in users' profiles.

For safety, automatic cleanup is disabled by default on new installations. An administrator can enable it at **Site administration > Plugins > Local plugins > Reset Course Badges** after reviewing its behavior.

Version 1.3 permanently deletes zero-recipient badge definitions instead of archiving them. Moodle's default deletion mode archives a badge, leaving it visible as unavailable. Issued badges continue to be skipped before deletion is attempted.

Version 1.4 implements Moodle's two safe deletion modes for reused courses. Zero-recipient badge definitions are permanently deleted. Badge definitions with recipients are archived using Moodle's "delete and keep existing issued badges" behavior, so they are removed from the active course badge list while users retain their awards.

Version 1.5 prepares the plugin for public distribution. Automatic cleanup now requires explicit administrator opt-in on new installations.

Course badge managers also receive a **Remove outdated course badges** navigation action. With one confirmation, it permanently deletes zero-recipient definitions and archives recipient-bearing definitions while retaining all issued badges in users' profiles.

## Clean a course that was restored before the automation ran

Run this command from the Moodle root, replacing `32` with the exact course ID:

`php local/resetcoursebadges/cli/cleanup.php --courseid=32`

The command prints the course name, the number of unused badge definitions deleted, and the number of recipient-bearing definitions archived with issued badges preserved.
