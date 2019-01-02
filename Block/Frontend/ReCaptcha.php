<?php
/**
 * MageSpecialist
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magespecialist.it so we can send you a copy immediately.
 *
 * @category   MSP
 * @package    MSP_ReCaptcha
 * @copyright  Copyright (c) 2017 Skeeller srl (http://www.magespecialist.it)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace MSP\ReCaptcha\Block\Frontend;

use Magento\Framework\Json\DecoderInterface;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\View\Element\Template;
use MSP\ReCaptcha\Model\Config;
use MSP\ReCaptcha\Model\LayoutSettings;

class ReCaptcha extends Template
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $data;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var LayoutSettings
     */
    private $layoutSettings;

    /**
     * @var string
     */
    private $reCaptchaId;

    /**
     * @var string
     */
    private $reCaptchaContainerId;

    /**
     * ReCaptcha constructor.
     * @param Template\Context $context
     * @param DecoderInterface $decoder
     * @param EncoderInterface $encoder
     * @param LayoutSettings $layoutSettings
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        DecoderInterface $decoder,
        EncoderInterface $encoder,
        LayoutSettings $layoutSettings,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->data = $data;
        $this->decoder = $decoder;
        $this->encoder = $encoder;
        $this->layoutSettings = $layoutSettings;
    }

    /**
     * Get public reCaptcha key
     * @return string
     */
    public function getPublicKey()
    {
        return $this->config->getPublicKey();
    }

    /**
     * @inheritdoc
     */
    public function getJsLayout()
    {
        $layout = $this->decoder->decode(parent::getJsLayout());

        $reCaptchaId = $this->getReCaptchaId();
        $layout['components'][$reCaptchaId] = $layout['components']['msp-recaptcha'];
        $layout['components'][$reCaptchaId]['reCaptchaId'] = $reCaptchaId;
        unset($layout['components']['msp-recaptcha']);

        $layout['components'][$this->getReCaptchaId()]['settings'] = $this->layoutSettings->getCaptchaSettings();
        return $this->encoder->encode($layout);
    }

    /**
     * Get reCaptcha container unic ID
     * @return string
     */
    public function getReCaptchaContainerId()
    {
        if (null === $this->reCaptchaContainerId) {
            $this->reCaptchaContainerId = 'msp-recaptcha-container-' . md5($this->getNameInLayout());
        }

        return $this->reCaptchaContainerId;
    }

    /**
     * Get reCaptcha unic ID
     * @return string
     */
    public function getReCaptchaId()
    {
        if (null === $this->reCaptchaId) {
            $this->reCaptchaId = 'msp-recaptcha-' . md5($this->getNameInLayout());
        }

        return $this->reCaptchaId;
    }
}
