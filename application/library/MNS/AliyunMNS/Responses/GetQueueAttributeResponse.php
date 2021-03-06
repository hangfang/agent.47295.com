<?php
namespace MNS\AliyunMNS\Responses;

use MNS\AliyunMNS\Constants;
use MNS\AliyunMNS\Model\QueueAttributes;
use MNS\AliyunMNS\Exception\MnsException;
use MNS\AliyunMNS\Exception\QueueNotExistException;
use MNS\AliyunMNS\Exception\InvalidArgumentException;
use MNS\AliyunMNS\Responses\BaseResponse;
use MNS\AliyunMNS\Common\XMLParser;

class GetQueueAttributeResponse extends BaseResponse
{
    private $attributes;

    public function __construct()
    {
        $this->attributes = NULL;
    }

    public function getQueueAttributes()
    {
        return $this->attributes;
    }

    public function parseResponse($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        if ($statusCode == 200) {
            $this->succeed = TRUE;
        } else {
            $this->parseErrorResponse($statusCode, $content);
        }

        $xmlReader = $this->loadXmlContent($content);

        try {
            $this->attributes = QueueAttributes::fromXML($xmlReader);
        } catch (\Exception $e) {
            throw new MnsException($statusCode, $e->getMessage(), $e);
        } catch (\Throwable $t) {
            throw new MnsException($statusCode, $t->getMessage());
        }

    }

    public function parseErrorResponse($statusCode, $content, MnsException $exception = NULL)
    {
        $this->succeed = FALSE;
        $xmlReader = $this->loadXmlContent($content);

        try {
            $result = XMLParser::parseNormalError($xmlReader);
            if ($result['Code'] == Constants::QUEUE_NOT_EXIST)
            {
                throw new QueueNotExistException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            throw new MnsException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
        } catch (\Exception $e) {
            if ($exception != NULL) {
                throw $exception;
            } elseif($e instanceof MnsException) {
                throw $e;
            } else {
                throw new MnsException($statusCode, $e->getMessage());
            }
        } catch (\Throwable $t) {
            throw new MnsException($statusCode, $t->getMessage());
        }
    }
}

?>
