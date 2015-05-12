<?php

/**
 * Class crud Controller
 */
class CrudController extends AdminController
{
    /**
     * @var string $сommand
     */
    private $command;

    /**
     * @var string $firstAttribute
     */
    private $firstAttribute;

    /**
     * @var string $secondAttribute
     */
    private $secondAttribute;

    /**
     * Construct
     */
    public function __construct()
    {
        $arguments = Tools::getValue('cli_argv', false);

        if (is_array($arguments)) {
            if (isset($arguments[1])) {
                $this->command = $arguments[1];

                if (isset($arguments[2])) {
                    $this->firstAttribute = $arguments[2];

                    if (isset($arguments[3])) {
                        $this->secondAttribute = $arguments[3];
                    }
                }
            }
        }

        switch ($this->command) {
            case 'cache':
                $endResult = $this->deleteCache('cache/');

                if ($endResult) {
                    echo "Delete was success! All cache was clean.\n";
                } else {
                    echo "Something go wrong, maybe you don`t have access for cache folder\n";
                }

                break;
            case 'addhook':
                $this->createNewHook($this->firstAttribute);
                break;
            case 'domain':
                $this->changeDomain($this->firstAttribute);
                break;
            case 'linkhook':
                $endResult = $this->linkHook($this->firstAttribute, $this->secondAttribute);

                switch ($endResult) {
                    case 1:
                        echo "No such module in data base.\n";
                        break;
                    case 2:
                        echo "No such hook in data base.\n";
                        break;
                    case 3:
                        echo "Such hook with module already axist.\n";
                        break;
                    case 4:
                        echo "You link was attached to this hook.\n";
                        break;
                }

                break;
            default:
                echo "---------------------- Commands ------------------------\n";
                echo "cache                                 - remove cache\n";
                echo "domain [domainname]                   - change site domain\n";
                echo "addhook [hookname]                    - add hook to site\n";
                echo "linkhook [modulename] [hookname]      - add hook to site\n";
                break;
        }
    }

    /**
     * Delete cache
     *
     * @param string $path
     *
     * @return bool
     */
    public function deleteCache($path)
    {
        foreach (glob("{$path}/*") as $file) {
            if (is_dir($file)) {
                $this->deleteCache($file);
            } else {
                unlink($file);
            }
        }

        return true;
    }

    /**
     * Create new hook
     *
     * @param $name string
     */
    public function createNewHook($name)
    {
        $sql = "SELECT `title` FROM ps_hook WHERE `name` = '$name'";

        $result = Db::getInstance()->executeS($sql);

        if ($result[0]['title'] == $name) {
            echo "This hook already axist.\n";

            return;
        } else {
            Db::getInstance()->insert('hook', [
                'name'        => $name,
                'title'       => $name,
                'description' => 'This is a custom hook!',
            ]);
        }
    }

    /**
     * Change domain
     *
     * @param $newDomain
     */
    public function changeDomain($newDomain)
    {
        $sql = "UPDATE `ps_shop` SET name = '$newDomain' WHERE id_shop = '1'";
        Db::getInstance()->execute($sql);

        $sql = "UPDATE `ps_shop_url` SET domain = '$newDomain' WHERE id_shop = '1'";
        Db::getInstance()->execute($sql);

        $sql = "UPDATE `ps_shop_url` SET domain_ssl = '$newDomain' WHERE id_shop = '1'";
        Db::getInstance()->execute($sql);

        $sql = "UPDATE `ps_configuration` SET value = '$newDomain' WHERE name IN ('PS_SHOP_DOMAIN', 'PS_SHOP_DOMAIN_SSL', 'PS_SHOP_NAME')";
        Db::getInstance()->execute($sql);
    }

    /**
     * Link hook with module
     *
     * @param $module
     * @param $hookName
     *
     * @return int
     */
    public function linkHook($module, $hookName)
    {
        $sql = "SELECT `id_module` FROM ps_module WHERE `name` = '$module'";
        $mod = Db::getInstance()->executeS($sql);
        if ($mod[0]['id_module'] == "") {
            return 1;
        }

        $sql  = "SELECT `id_hook` FROM ps_hook WHERE `name` = '$hookName'";
        $hook = Db::getInstance()->executeS($sql);
        if ($hook[0]['id_hook'] == "") {
            return 2;
        }

        $myMode = $mod[0]['id_module'];
        $myHook = $hook[0]['id_hook'];

        $sql        = "SELECT * FROM `ps_hook_module` WHERE `id_module` = '$myMode' AND  `id_hook` = '$myHook'";
        $validation = Db::getInstance()->executeS($sql);

        if ($validation[0]['id_module'] != "" && $validation[0]['id_hook'] != "") {
            return 3;
        }

        $sql = "INSERT INTO `ps_hook_module` VALUES ('$myMode', 1, '$myHook', 1)";
        Db::getInstance()->execute($sql);

        return 4;
    }
}
