<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Models\Subscriber;
use App\Http\Resources\Subscriber as SubscriberResource;
use App\Http\Resources\SubscriberCollection;
use Illuminate\Support\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use League\Csv\Writer;

class SubscriberController extends Controller
{
    protected function query()
    {
        return QueryBuilder::for(Subscriber::class)
            ->allowedFilters([
                'name',
                'email',
                'state'
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'name',
                'email',
                'state'
            ])
            ->with('fields:id,title,type')
            ->get();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = $this->query();

        return new SubscriberCollection($subscribers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubscriberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriberRequest $request)
    {
        $subscriber = Subscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'state' => $request->state
        ]);

        if (!empty($request->field_ids)) {
            $subscriber->fields()->sync($request->field_ids);
            $subscriber->save();
        }

        return response()->json([
            'message' => 'Subscriber created successfully.',
            'data' => new SubscriberResource($subscriber)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $subscriber)
    {
        return new SubscriberResource($subscriber);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubscriberRequest  $request
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber)
    {
        $subscriber->update($request->all());

        if (!empty($request->field_ids)) {
            $subscriber->fields()->sync($request->field_ids);
            $subscriber->save();
        }

        return response()->json([
            'message' => 'Subscriber updated successfully.',
            'data' => new SubscriberResource($subscriber)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return response()->json([
            'message' => 'Subscriber deleted successfully.',
            'data' => new SubscriberResource($subscriber)
        ]);
    }

    public function export()
    {
        $header = ['Name', 'Email', 'State'];

        $subscribers = $this->query();

        $data = $subscribers->map(fn ($subscriber) => [
            'name' => $subscriber['name'],
            'email' => $subscriber['email'],
            'state' => $subscriber['state']
        ])->toArray();

        $writer = Writer::createFromString('');
        $writer->insertOne($header);
        $writer->insertAll($data);

        $timestamp = preg_replace('/[^0-9]/', '', Carbon::now());
        $filename = 'subscribers_'.$timestamp.'.csv';

        return response()->streamDownload(function() use ($writer) {
            echo $writer->getContent();
        }, $filename);
    }
}
