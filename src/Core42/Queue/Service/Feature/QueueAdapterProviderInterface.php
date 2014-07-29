<?php
namespace Core42\Queue\Service\Feature;

interface QueueAdapterProviderInterface
{
    public function getQueueAdapter();
}
