<?php
declare(strict_types=1);

namespace tp\mytool\package\dto;

use tp\mytool\package\framework\exception\RuntimeException;
use tp\mytool\package\framework\lang\ZhCn;
use tp\mytool\package\util\Helper;
use ReflectionException;
use think\App;
use think\Container;
use think\exception\ValidateException;
use think\Lang;
use think\Validate;

/**
 * Class AbstractParamDTO
 *
 * @method static array validateMessage() 错误信息提示
 *
 * @package tp\mytool\package\dto
 */
abstract class AbstractParamDTO extends AbstractBasic
{
    /**
     * 校验规则
     *
     * @return array
     */
    abstract protected static function validateRule(): array;

    /**
     * 是否需要校验
     * @var bool
     */
    protected static $is_validate = true;

    /**
     * 是否需要重载语言包
     * @var bool
     */
    protected static $is_load_lang = true;

    /**
     * @param bool $is_validate
     */
    public static function setIsValidate(bool $is_validate): void
    {
        static::$is_validate = $is_validate;
    }

    /**
     * @param bool $is_load_lang
     */
    public static function setIsLoadLang(bool $is_load_lang): void
    {
        static::$is_load_lang = $is_load_lang;
    }

    /**
     * @param array $param
     * @param bool $newInstance
     *
     * @return AbstractBasic
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public static function getDTO(array $param, bool $newInstance = false): AbstractBasic
    {
        if (static::$is_validate) {
            static::validate($param);
        }

        return Helper::copyParamByClass($param, static::class, '', $newInstance);
    }

    /**
     * 参数校验
     *
     * @param array $param
     */
    public static function validate(array $param): void
    {
        static::$is_validate = true; // 重制状态标识

        $rule = static::validateRule();
        if (!empty($rule)) {
            $validate = new Validate();

            if (static::$is_load_lang) {
                $validate->setLang(static::loadLang());
            }

            if (method_exists(static::class, 'validateMessage')) {
                $message = static::validateMessage();
                $validate->message($message);
            }

            $result = $validate->check($param, $rule);
            if ($result !== true) {
                $error = empty($validate->getError()) ? '' : $validate->getError();
                throw new ValidateException($error);
            }
        }
    }

    /**
     * 重载自定义语言包
     *
     * @return Lang
     */
    protected static function loadLang(): Lang
    {
        static::$is_load_lang = false; // 修改状态标识,避免多次加载

        $container = Container::getInstance();

        /** @var Lang $lang */
        $lang = $container->make('lang');

        /** @var ZhCn $myToolLang */
        $myToolLang = $container->make('tp-mytool.lang');

        $lang->load($myToolLang->getLangFile());

        return $lang;
    }
}