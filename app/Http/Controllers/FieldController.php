<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\Field;
use App\Http\Resources\Field as FieldResource;
use App\Http\Resources\FieldCollection;
use Illuminate\Support\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use League\Csv\Writer;

class FieldController extends Controller
{
    protected function query()
    {
        return QueryBuilder::for(Field::class)
            ->allowedFilters([
                'title',
                'type'
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'title',
                'type',
            ])
            ->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = $this->query();

        return new FieldCollection($fields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFieldRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFieldRequest $request)
    {
        $field = Field::create([
            'title' => $request->title,
            'type' => $request->type
        ]);

        return response()->json([
            'message' => 'Field created successfully.',
            'data' => new FieldResource($field)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        return new FieldResource($field);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFieldRequest  $request
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFieldRequest $request, Field $field)
    {
        $field->update($request->all());

        return response()->json([
            'message' => 'Field updated successfully.',
            'data' => new FieldResource($field)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        $field->delete();

        return response()->json([
            'message' => 'Field deleted successfully.',
            'data' => new FieldResource($field)
        ]);
    }

    public function export()
    {
        $header = ['Title', 'Type'];

        $subscribers = $this->query();

        $data = $subscribers->map(fn ($subscriber) => [
            'title' => $subscriber['title'],
            'type' => $subscriber['type']->value,
        ])->toArray();

        $writer = Writer::createFromString('');
        $writer->insertOne($header);
        $writer->insertAll($data);

        $timestamp = preg_replace('/[^0-9]/', '', Carbon::now());
        $filename = 'fields_'.$timestamp.'.csv';

        return response()->streamDownload(function() use ($writer) {
            echo $writer->getContent();
        }, $filename);
    }

}
