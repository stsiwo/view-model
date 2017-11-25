<?php

namespace App\Http\ViewModel;

use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use App\Model\Dictionary;
use App\Model\Word;
use Illuminate\Database\Eloquent\Collection;


class Dic implements JsonSerializable, Jsonable, Arrayable
{
  /**
   * dictionary id (App\Model\Dictionary)
   *
   * @column id
   */
  protected $dicId;

  /**
   * dictionary name (App\Model\Dictionary)
   *
   * @column name
   */
  protected $dicName;

  /**
   * user id (App\User)
   *
   * @column id
   */
  protected $userId;

  /**
   * words array (App\Model\Word)
   *
   * @column n/a
   */
  protected $words;

  /**
   * constructor
   *
   * @param array $attrs
   */
  public function __construct(array $attrs = null)
  {
    $this->words = collect([]);

    if (!is_null($attrs)) {
      $props = get_object_vars($this);
      foreach ($props as $propKey => $propVal) {
        $this->$propKey = $attrs[$propKey];
      }
    }
  }

  /**
   * second constructor with Dictionary model and its relations (User, Word)
   *
   * @param array $attrs
   */
  public static function createWith(Dictionary $dic)
  {
    $instance = new self();
    $instance->assignProps($dic);
    return $instance;
  }

  /**
   * assign this object's property
   *
   * @param App\Model\Dictionary $dic
   */
  protected function assignProps(Dictionary $dic)
  {
    $this->dicId = $dic->id;
    $this->dicName = $dic->name;
    $this->userId = $dic->user_id;
    $this->words = $this->filterWordsCollection($dic->words);
  }

  /**
   * filter attribute of Word model (only id and name)
   *
   * @param Collection $words
   */
  protected function filterWordsCollection(Collection $words)
  {
    return $words->map(function($word) {
      return ['id' => $word->id, 'name' => $word->name];
    });
  }

  /**
   * get dictionary id
   *
   * @return int
   */
  public function getDicId() {
    return $this->dicId;
  }

  /**
   * set dictionary id
   *
   * @return int
   */
  public function setDicId(int $DicId) {
    $this->id = $dicId;
    return $this->dicId;
  }

  /**
   * get dictionary name
   *
   * @return string
   */
  public function getDicName() {
    return $this->dicName;
  }

  /**
   * set dictionary name
   *
   * @return string
   */
  public function setDicName(int $DicName) {
    $this->DicName = $dicName;
    return $this->dicName;
  }

  /**
   * get user id
   *
   * @return int
   */
  public function getUserId() {
    return $this->userId;
  }

  /**
   * set user id
   *
   * @return string
   */
  public function setUserId(int $userId) {
    $this->userId = $userId;
    return $this->userId;
  }

  /**
   * add word to words property of this object
   *
   * @return Collection
   */
  public function addWord(Word $word) {
    $this->words->push($word);
    return $this->words;
  }

  /**
   * get words
   *
   * @return Collection
   */
  public function getWords()
  {
    return $this->words;
  }

  /**
   * allow dynamic access to each property through getter method
   *
   * @return mixed
   */
  public function __get($name) {
    $function = "get" . ucfirst($name);
    return $this->$function();
  }

  /**
   * allow dynamic access to each property through setter method
   *
   * @return mixed
   */
  public function __set($key, $value) {
    $function = "set" . ucfirst($key);
    return $this->$function($value);
  }

  /**
   * make array of this object
   *
   * @return mixed
   */
  public function toArray() {
    return [
      'dicId' => $this->dicId,
      'dicName' => $this->dicName,
      'userId' => $this->userId,
      'words' => $this->words->toArray(),
    ];
  }

  /**
   * make json of this object
   *
   * @return mixed
   */
  public function toJson($option = 0) {
    return json_encode($this, $option);
  }

  /**
   * JsonSerialize interface method
   *
   * @return mixed
   */
  public function JsonSerialize()
  {
    return $this->toArray();
  }
}
