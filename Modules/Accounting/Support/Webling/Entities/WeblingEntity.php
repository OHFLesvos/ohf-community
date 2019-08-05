<?php

namespace Modules\Accounting\Support\Webling\Entities;

use Modules\Accounting\Support\Webling\WeblingClient;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Carbon\Carbon;

abstract class WeblingEntity
{
    protected $id;

    protected $children = [];
    protected $parents = [];

    protected static function getObjectName(): string
    {
        if (isset(static::$objectName)) {
            return static::$objectName;
        }
        return strtolower(class_basename(static::class));
    }

    public static function ids(): array
    {
        $webling = resolve(WeblingClient::class);
        $response = $webling->api()->get(self::getObjectName());
        return collect($response->getData())->first();
    }

    public static function find($id)
    {
        if (is_array($id)) {
            if (count($id) == 1) {
                return [self::findById($id[0])];
            } else {
                $webling = resolve(WeblingClient::class);
                $response = $webling->api()->get(self::getObjectName() . '/' . implode(',', $id));
                return collect($response->getData())
                    ->map(function($data) {
                        return self::createFromResponseData($data);
                    });   
            }
        }
        return self::findById($id);
    }

    public static function findOrFail(int $id)
    {
        $result = self::findById($id);
        if ($result == null) {
            throw new ModelNotFoundException('Object of type \'' . self::getObjectName() . '\' with ID ' . $id . ' not found.');
        }
        return $result;
    }

    private static function findById(int $id)
    {
        $webling = resolve(WeblingClient::class);
        $response = $webling->api()->get(self::getObjectName() . '/' . $id);
        if ($response->getStatusCode() == 404) {
            return null;
        }
        return self::createFromResponseData($response->getData(), $id);
    }

    public static function all(): Collection
    {
        $webling = resolve(WeblingClient::class);
        $response = $webling->api()->get(self::getObjectName() . '?format=full');
        return collect($response->getData())
            ->map(function($data) {
                return self::createFromResponseData($data);
            });
    }

    public static function filtered($filter): Collection
    {
        $webling = resolve(WeblingClient::class);
        $response = $webling->api()->get(self::getObjectName() . '?format=full&filter=' . $filter);
        return collect($response->getData())
            ->map(function($data) {
                return self::createFromResponseData($data);
            });
    }

    private static function createFromResponseData(array $data, int $id = null)
    {
        $clazz = static::class;
        $obj = new $clazz();

        // ID
        $obj->id = $id != null ? $id : (isset($data['id']) ? $data['id'] : null);

        // Properties
        if (isset($data['properties'])) {
            foreach ($data['properties'] as $k => $v) {
                if (isset(static::$dates) && in_array($k, static::$dates)) {
                    $v = new Carbon($v);
                }
                $obj->$k = $v;
            }
        }

        // Children IDs
        if (isset($data['children'])) {
            foreach ($data['children'] as $k => $v) {
                $obj->children[$k] = $v;
            }
        }

        // Parent IDs
        if (isset($data['parents'])) {
            foreach ($data['parents'] as $v) {
                $obj->parents[] = $v;
            }
        }

        return $obj;
    }

    protected function hasMany($clazz, $childRelation = null) {
        if ($childRelation == null) {
            $childRelation = $clazz::getObjectName();
        }
        if (isset($this->children[$childRelation])) {
            return collect($clazz::find($this->children[$childRelation]));
        }
        return collect();
    }

    protected function belongsTo($clazz) {
        if (isset($this->parents[0])) {
            return $clazz::find($this->parents[0]);
        }
        return null;
    }

    public function __get($var)
    {
        // $func = $var;
        // if (method_exists($this, $func)) {
        //     return $this->$func();
        // }
        if ($var == 'id') {
            return $this->id;
        }
        throw new \Exception('Undefined property: ' . get_called_class().'::$'.$var);
    }

    public final function save()
    {
        // TODO
    }

    public final function delete()
    {
        // TODO
    }
}
