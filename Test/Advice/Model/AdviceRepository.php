<?php

namespace Test\Advice\Model;

use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class AdviceRepository
 * @package Test\Advice\Model
 */
class AdviceRepository
{
    const API_URL = 'https://api.adviceslip.com/';

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * AdviceRepository constructor.
     * @param Json $serializer
     */
    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param int $id
     * @return string
     */
    public function getById($id)
    {
        if ($id) {
            $url = self::API_URL . 'advice/' . $id;
            return $this->getAdvice($url);
        }

        return '';
    }

    /**
     * @param string $query
     * @return array
     */
    public function getBySearch($query)
    {
        if ($query) {
            $url = self::API_URL . 'advice/search/' . $query;
            return $this->getAdvices($url);
        }

        return [];
    }

    /**
     * @return string
     */
    public function getRandomAdvice()
    {
        $url = self::API_URL . 'advice';
        return $this->getAdvice($url);
    }

    /**
     * @param string $url
     * @return array
     */
    protected function getAdvices($url)
    {
        $data = $this->getDataFromApi($url);
        if ($data
            && isset($data['total_results'])
            && $data['total_results'] > 0
            && isset($data['slips']))
        {
            $advices = [];
            foreach ($data['slips'] as $advice) {
                if (isset($advice['advice'])) {
                    $advices[] = $advice['advice'];
                }
            }

            return count($advices) > 0 ? $advices : [];
        }
    }

    /**
     * @param string $url
     * @return string
     */
    protected function getAdvice($url)
    {
        $data = $this->getDataFromApi($url);
        if ($data && isset($data['slip']) && isset($data['slip']['advice'])) {
            return $data['slip']['advice'];
        }

        return '';
    }

    /**
     * @param string $url
     * @return array
     */
    protected function getDataFromApi($url)
    {
        try {
            $content  = file_get_contents($url);
            if ($content) {
                $data = $this->serializer->unserialize($content);
                return $data;
            }
        } catch (\Exception $e) {

        }

        return [];
    }
}