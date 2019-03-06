<?php

namespace Test\Advice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;


/**
 * Class Configuration
 * @package Test\Advice\Helper
 */
class Configuration extends AbstractHelper
{
    const XML_PATH_ENABLE_ADVICE_ON_PRODUCT_PAGE = 'advice/general/enabled';
    const XML_PATH_HEADER_ADVICE_TEXT = 'advice/general/header_text';

    /**
     * @return bool
     */
    public function enableAdviceOnProductPage()
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_ENABLE_ADVICE_ON_PRODUCT_PAGE, ScopeInterface::SCOPE_STORE);

    }

    /**
     * @return string
     */
    public function getHeaderAdviceText()
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_HEADER_ADVICE_TEXT, ScopeInterface::SCOPE_STORE);
    }
}