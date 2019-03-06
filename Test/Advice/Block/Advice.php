<?php
namespace Test\Advice\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface as UrlEncoderInterface;
use Test\Advice\Helper\Configuration;
use Test\Advice\Model\AdviceRepository;

/**
 * Class Advice
 * @package Test\Advice\Block
 */
class Advice extends \Magento\Catalog\Block\Product\View
{
    /**
     * @var Configuration
     */
    protected $helper;

    /**
     * @var AdviceRepository
     */
    protected $adviceRepository;

    /**
     * Advice constructor.
     * @param Context $context
     * @param UrlEncoderInterface $urlEncoder
     * @param EncoderInterface $jsonEncoder
     * @param StringUtils $string
     * @param Product $productHelper
     * @param ConfigInterface $productTypeConfig
     * @param FormatInterface $localeFormat
     * @param Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param PriceCurrencyInterface $priceCurrency
     * @param Configuration $helper
     * @param AdviceRepository $adviceRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlEncoderInterface $urlEncoder,
        EncoderInterface $jsonEncoder,
        StringUtils $string,
        Product $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        Configuration $helper,
        AdviceRepository $adviceRepository,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->adviceRepository = $adviceRepository;

        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        $adviceEnabled = $this->helper->enableAdviceOnProductPage();

        if ($adviceEnabled && !empty($this->getAdvices())) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * @return string
     */
    public function getAdviceHeaderText()
    {
        return $this->helper->getHeaderAdviceText();
    }

    /**
     * @return array
     */
    public function getAdvices()
    {
        $productName = $this->getProduct()->getName();
        $advices = $this->adviceRepository->getBySearch($productName);
        if (!empty($advices)) {
            return $advices;
        }

        $id = 1; //default advice for product

        if ($this->adviceRepository->getById($id)) {
            $advices[] = $this->adviceRepository->getById($id);
        }
        return $advices;
    }
}