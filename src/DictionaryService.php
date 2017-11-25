<?php
namespace App\Services\Dictionary;

use App\Repositories\Dictionary\DictionaryRepoInterface;
use App\Repositories\Word\WordRepoInterface;
use App\Repositories\User\UserRepoInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\ViewModel\Dic;


class DictionaryService implements DictionaryServiceInterface
{
    /**
    * instance of DictionaryRepoInterface
    *
    * @var App\Services\DictionaryRepoInterface
    */
    protected $dicRepo;

    /**
    * instance of WordRepoInterface
    *
    * @var App\Services\WordRepoInterface
    */
    protected $wordRepo;

    /**
    * instance of UserRepoInterface
    *
    * @var App\Services\UserRepoInterface
    */
    protected $userRepo;

    public function __construct(DictionaryRepoInterface $dicRepo, WordRepoInterface $wordRepo, UserRepoInterface $userRepo) {
        $this->dicRepo = $dicRepo;
        $this->wordRepo = $wordRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * get list of dictionaries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListOfDictionaries()
    {
        // retrieve all dictionar
        return $this->dicRepo->getAll();
    }

    /**
     * get list of dictionaries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListOfDictionariesByUser(\App\User $user)
    {
        return $user->dictionaries;
    }

    /**
     * get list of words.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListOfWords()
    {
        return $this->wordRepo->getAll();
    }

    /**
     * get list of words associated with specific user.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListOfWordsByUser(\App\User $user)
    {
        return $user->words;
    }

    /**
     * save dictionary with words
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function registerDictionaryWithItsWords(array $data)
    {
        $dic = null;

        \DB::transaction(function() use ($data, &$dic){
          // get current user
          $user = Auth::user();
          // create new Dictionary
          $dic = $this->dicRepo->create(['name' => $data['dicName'], 'user_id' => $user->id, 'num_of_words' => count($data['words'])]);
          // retrieve words by id
          $words = $this->wordRepo->findWords(extractAttr($data['words'], "id"));
          // save those words to the Dcitionary using relation
          $dic->words()->saveMany($words);
        });

        // return this dictonary Model
        return $dic;
    }

    /**
     * find dictionary with words by id.
     *
     * @param int $id
     * @return \App\Http\ViewModel\Dic
     */
    public function findDictionaryWithItsWords(int $id)
    {
        // find dictionary with eager loading by its id
        $dic = $this->dicRepo->findDictionaryWithItsWords($id);
        // create View Model for show page
        $viewModel = Dic::createWith($dic);

        return $viewModel;
    }

    /**
     * update dictionary with words.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function updateDictionaryWithItsWords(array $data)
    {
        // retrieve dictionary by id
        $dic = $this->dicRepo->find($data['dicId']);

        \DB::transaction(function() use ($data, &$dic) {
          // name update if necessary
          $this->dicRepo->update($dic->id, ['name' => $data['dicName'], 'num_of_words' => count($data['words'])]);
          // retrieve words associated with this dictionary (relations)
          // if new word ids and database data is differed, delete all data in dic_word
          // if not, leave it out
          $dic->words()->sync(extractAttr($data['words'], 'id'));
        });

        return $dic;
    }

    /**
     * destroy dictionary with its words (using database cascade)
     *
     * @param int $id
     */
    public function destroyDictionaryWithItsWords(int $id)
    {
        // delete dictionary by its id also associateed words (use cascade)
        $this->dicRepo->destroy($id);
    }
}
