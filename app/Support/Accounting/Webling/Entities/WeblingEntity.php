<?php

namespace App\Support\Accounting\Webling\Entities;

use App\Support\Accounting\Webling\Exceptions\ConnectionException;
use App\Support\Accounting\Webling\WeblingClient;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Webling\API\ClientException;

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

    public static function find($id)
    {
        if (is_array($id)) {
            $webling = resolve(WeblingClient::class);
            $data = $webling->getObjects(self::getObjectName(), $id);
            return collect($data)
                ->map(fn ($data) => self::createFromResponseData($data));
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
        try {
            $data = $webling->getObject(self::getObjectName(), $id);
            if ($data != null) {
                return self::createFromResponseData($data);
            }
            return null;
        } catch (ClientException $e) {
            throw new ConnectionException($e->getMessage());
        }
    }

    public static function all(): Collection
    {
        $webling = resolve(WeblingClient::class);
        try {
            $ids = $webling->listObjectIds(self::getObjectName());
            if ($ids != null) {
                $data = $webling->getObjects(self::getObjectName(), $ids);
                if ($data != null) {
                    return collect($data)
                        ->map(fn ($data) => self::createFromResponseData($data));
                }
            }
            throw new ModelNotFoundException('Objects of type \'' . self::getObjectName() . '\' not found.');
        } catch (ClientException $e) {
            throw new ConnectionException($e->getMessage());
        }
    }

    public static function filtered($filter): Collection
    {
        $webling = resolve(WeblingClient::class);
        try {
            $data = $webling->listObjectsUncached(self::getObjectName(), true, $filter);
            if ($data != null) {
                return collect($data)
                    ->map(fn ($data) => self::createFromResponseData($data));
            }
            throw new ModelNotFoundException('Objects of type \'' . self::getObjectName() . '\' not found.');
        } catch (ClientException $e) {
            throw new ConnectionException($e->getMessage());
        }
    }

    private static function createFromResponseData(array $data)
    {
        $clazz = static::class;
        $obj = new $clazz();

        // ID
        $obj->id = $data['id'] ?? null;

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

    public function parent()
    {
        return count($this->parents) > 0 ? $this->parents[0] : null;
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
        if ($var == 'parent') {
            return $this->parent();
        }
        throw new Exception('Undefined property: ' . get_called_class().'::$'.$var);
    }

    public static function createRaw($data)
    {
        $webling = resolve(WeblingClient::class);
        $id = $webling->storeObject(self::getObjectName(), $data);
        return self::find($id);
    }

    final public function save()
    {
        // TODO
    }

    final public function delete()
    {
        // TODO
    }
}
