<?php

namespace TaskinBirtan\LaravelNetgsmSms\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use TaskinBirtan\LaravelNetgsmSms\Netgsm;

trait SmsAble {
    /**
     * Modelde belirtilen tablo kolon değeri kullanılarak mesaj gönderimi sağlanacaktır.
     * @param string $column
     * @return string
     */
    abstract public function getSmsPhoneColumn($column = 'phone');

    public function sendSingleSms($message)
    {
        $column = static::getSmsPhoneColumn();
        $phone = $this->{$column};
        return Netgsm::sendSingleSms($phone, $message);
    }
    public function sendBulkSms(array $ids, $message)
    {
        $column = static::getSmsPhoneColumn();
        $targets = parent::whereIn($this->primaryKey, $ids)->get()->pluck($column);
        return Netgsm::sendBulkSms($targets, $message);
    }

}
