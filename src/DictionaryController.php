<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dictionary\DictionaryServiceInterface;
use App\Http\Requests\DictionaryRequest;

class DictionaryController extends Controller
{
    /**
    * instance of DictionaryServiceInterface
    *
    * @var App\Services\DictionaryServiceInterface
    */
    protected $service;

    public function __construct(DictionaryServiceInterface $service)
    {
        $this->service = $service;
        $this->middleware('auth:web');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $dictionaries = $this->service->getListOfDictionariesByUser($user);
        return view('dictionaries.index', ['dictionaries' => $dictionaries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = \Auth::user();
        $words = $this->service->getListOfWordsByUser($user);
        return view('dictionaries.create', ['words' => $words]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\DictionaryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DictionaryRequest $request)
    {
        $this->service->registerDictionaryWithItsWords($request->input());
        return ['redirect' => route('dictionaries.index')];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dictionaryWords = $this->service->findDictionaryWithItsWords($id);
        return view('dictionaries.show', ['dictionaryWords' => $dictionaryWords->toJson()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $user = \Auth::user();
      $dictionaryWords = $this->service->findDictionaryWithItsWords($id);
      $words = $this->service->getListOfWordsByUser($user);
      return view('dictionaries.edit', ['dictionaryWords' => $dictionaryWords->toJson(), 'words' => $words]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\DictionaryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DictionaryRequest $request, $id)
    {
      $this->service->updateDictionaryWithItsWords($request->input());
      return ['redirect' => route('dictionaries.index')];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->service->destroyDictionaryWithItsWords($id);
        return ['redirect' => route('dictionaries.index')];
    }
}
