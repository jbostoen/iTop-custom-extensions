<?php
// Copyright (C) 2016 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Lesser General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
/**
 * @copyright   Copyright (C) 2016 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(__DIR__.'/mail_mimedecode.class.inc.php');
require_once(__DIR__.'/net_pop3.class.inc.php');
require_once(__DIR__.'/rawemailmessage.class.inc.php');
require_once(__DIR__.'/messagefrommailbox.class.inc.php');
require_once(__DIR__.'/emailmessage.class.inc.php');
if (interface_exists('iBackgroundProcess'))
{
	// When running the stand-alone decoding of a message (from a file) we don't need this
	require_once(__DIR__.'/emailbackgroundprocess.class.inc.php');
	require_once(__DIR__.'/emailprocessor.class.inc.php');
	require_once(__DIR__.'/testemailprocessor.class.inc.php');
	require_once(__DIR__.'/mailinboxesemailprocessor.class.inc.php');
}
if (class_exists('DBObject'))
{
	// When running the stand-alone decoding of a message (from a file) we don't need this
	require_once(__DIR__.'/_mailinboxbase.class.inc.php');
	require_once(__DIR__.'/emailreplica.class.inc.php');
	require_once(__DIR__.'/triggeronmailupdate.class.inc.php');
}
require_once(__DIR__.'/emailsource.class.inc.php');
require_once(__DIR__.'/imapemailsource.class.inc.php');
require_once(__DIR__.'/pop3emailsource.class.inc.php');
require_once(__DIR__.'/testemailsource.class.inc.php');
