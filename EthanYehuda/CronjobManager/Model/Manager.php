<?php

Namespace EthanYehuda\CronjobManager\Model;

use Magento\Cron\Observer\ProcessCronQueueObserver;
use \Magento\Cron\Model\Schedule;

class Manager extends ProcessCronQueueObserver
{
	public function saveCronJob($jobCode, $time)
	{
		$filteredTime = $this->filterTimeInput($time);
		
		/**
		 * @var $schedule \Magento\Cron\Model\Schedule
		 */
		$schedule = $this->_scheduleFactory->create()
			->setJobCode($jobCode)
			->setStatus(Schedule::STATUS_PENDING)
			->setCreatedAt(strftime('%Y-%m-%d %H:%M:%S', $this->timezone->scopeTimeStamp()))
			->setScheduledAt($filteredTime);

		$schedule->getResource()->save($schedule);
	}
	
	protected function filterTimeInput($time) 
	{
		$matches = [];
		preg_match('/(\d+-\d+-\d+)T(\d+:\d+)/', $time, $matches);
		$yearMonthDate = $matches[1];
		$hourMinuets = " " . $matches[2];
		return $yearMonthDate . $hourMinuets;
	}
}