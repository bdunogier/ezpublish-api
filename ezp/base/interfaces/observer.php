<?php
/**
 * Interface for observer, extended with support for certain events.
 * $event = 'update' means basically "updated" just as in normal observer code.
 *
 * @copyright Copyright (c) 2011, eZ Systems AS
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2.0
 * @package ezp
 * @subpackage base
 */
namespace ezp\base;
interface ObserverInterface// extends \SplObserver
{
    /**
     * Called when subject has been updated
     *
     * @param Observable $subject
     * @param string $event
     * @return Observer
     */
    public function update( ObservableInterface $subject, $event = 'update' );
}