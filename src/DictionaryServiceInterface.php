<?php
namespace App\Services\Dictionary;

interface DictionaryServiceInterface {

   /**
    * get list of dictionaries.
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function getListOfDictionaries();

   /**
    * get list of dictionaries.
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function getListOfDictionariesByUser(\App\User $user);

    /**
     * get list of words.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListOfWords();

    /**
     * get list of words associated with specific user.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListOfWordsByUser(\App\User $user);

    /**
     * save dictionary with words
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function registerDictionaryWithItsWords(array $data);

    /**
     * find dictionary with words by id.
     *
     * @param int $id
     * @return \App\Http\ViewModel\Dic
     */
    public function findDictionaryWithItsWords(int $id);

    /**
     * update dictionary with words.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function updateDictionaryWithItsWords(array $data);

    /**
     * destroy dictionary with its words (using database cascade)
     *
     * @param int $id
     */
    public function destroyDictionaryWithItsWords(int $id);
}
