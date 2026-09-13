<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * English language strings for Reset Course Badges.
 *
 * @package   local_resetcoursebadges
 * @copyright 2026 Nellie Deutsch
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['enabled'] = 'Remove outdated badges after course reset or restore';
$string['enabled_desc'] = 'When explicitly enabled, resetting or restoring a course automatically removes its outdated badge definitions. Zero-recipient definitions are deleted. Definitions with recipients are archived while all issued badges remain in users’ profiles. Site badges and badges in other courses are untouched. This setting is disabled by default on new installations.';
$string['pluginname'] = 'Reset Course Badges';
$string['privacy:metadata'] = 'The Reset Course Badges plugin does not store personal data.';
$string['bulkcleanuplink'] = 'Remove outdated course badges';
$string['bulkcleanuptitle'] = 'Remove outdated course badges';
$string['bulkcleanupconfirm'] = 'Remove all {$a->total} outdated badge definitions from “{$a->course}”? Moodle will permanently delete {$a->unissued} badges with no recipients and archive {$a->issued} badges while preserving every issued badge in users’ profiles.';
$string['bulkcleanupcomplete'] = 'Outdated course badges removed';
$string['bulkcleanupresult'] = 'Permanently deleted {$a->deleted} zero-recipient badge definitions. Archived {$a->archived} badge definitions while preserving all issued badges in users’ profiles.';
