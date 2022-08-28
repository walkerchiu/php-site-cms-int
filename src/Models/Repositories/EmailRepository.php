<?php

namespace WalkerChiu\SiteCMS\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Forms\FormTrait;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryTrait;
use WalkerChiu\Core\Models\Services\PackagingFactory;

class EmailRepository extends Repository
{
    use FormTrait;
    use RepositoryTrait;

    protected $instance;



    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = App::make(config('wk-core.class.site-cms.email'));
    }

    /**
     * @param String  $code
     * @param Array   $data
     * @param Bool    $is_enabled
     * @param Bool    $auto_packing
     * @return Array|Collection|Eloquent
     */
    public function list(string $code, array $data, $is_enabled = null, $auto_packing = false)
    {
        $instance = $this->instance;
        if ($is_enabled === true)      $instance = $instance->ofEnabled();
        elseif ($is_enabled === false) $instance = $instance->ofDisabled();

        $data = array_map('trim', $data);
        $repository = $instance->with(['langs' => function ($query) use ($code) {
                                    $query->ofCurrent()
                                          ->ofCode($code);
                                }, 'site'])
                                ->whereHas('langs', function ($query) use ($code) {
                                    return $query->ofCurrent()
                                                 ->ofCode($code);
                                })
                                ->when($data, function ($query, $data) {
                                    return $query->unless(empty($data['id']), function ($query) use ($data) {
                                                return $query->where('id', $data['id']);
                                            })
                                            ->unless(empty($data['site_id']), function ($query) use ($data) {
                                                return $query->where('site_id', $data['site_id']);
                                            })
                                            ->unless(empty($data['serial']), function ($query) use ($data) {
                                                return $query->where('serial', $data['serial']);
                                            })
                                            ->unless(empty($data['subject']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'subject')
                                                          ->where('value', 'LIKE', "%".$data['subject']."%");
                                                });
                                            })
                                            ->unless(empty($data['name']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'name')
                                                          ->where('value', 'LIKE', "%".$data['name']."%");
                                                });
                                            })
                                            ->unless(empty($data['description']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'description')
                                                          ->where('value', 'LIKE', "%".$data['description']."%");
                                                });
                                            });
                                })
                                ->orderBy('updated_at', 'DESC');

        if ($auto_packing) {
            $factory = new PackagingFactory(config('wk-site-cms.output_format'), config('wk-site-cms.pagination.pageName'), config('wk-site-cms.pagination.perPage'));
            $factory->setFieldsLang(['name', 'description', 'subject', 'content']);

            if (in_array(config('wk-site-cms.output_format'), ['array', 'array_pagination'])) {
                switch (config('wk-site-cms.output_format')) {
                    case "array":
                        $entities = $factory->toCollection($repository);
                        // no break
                    case "array_pagination":
                        $entities = $factory->toCollectionWithPagination($repository);
                        // no break
                    default:
                        $output = [];
                        foreach ($entities as $instance) {
                            $data = $instance->toArray();
                            array_push($output,
                                array_merge($data, [
                                    'site_name' => $record->site->findLang($code, 'name')
                                ])
                            );
                        }
                }
                return $output;
            } else {
                return $factory->output($repository);
            }
        }

        return $repository;
    }

    /**
     * @param Email         $instance
     * @param Array|String  $code
     * @return Array
     */
    public function show($instance, $code): array
    {
    }

    /**
     * @param Site    $site
     * @param String  $type
     * @param Int     $id
     * @return Bool
     */
    public function enableSetting($site, string $type, int $id): bool
    {
        if (empty($id))
            return $site->emails($type)->update(['is_enabled' => 0]);

        return $this->enableById($id);
    }
}
