<?php

namespace Pcea\Entity;

class Spent {
	private $id;
	private $name;
	private $amount;
	private $buyDate;
	private $buyer;

	/**
	 * Event associated
	 *
	 * @var \Pcea\Entity\Event
	 */
	private $event;

	/**
	 * Gets the value of id

	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Sets the value of id
	 *
	 * @param mixed $id
	 * @return self
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Gets the value of name

	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the value of name
	 *
	 * @param mixed $name
	 * @return self
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Gets the value of amount

	 * @return mixed
	 */
	public function getAmount() {
		return $this->amount;
	}
	
	/**
	 * Sets the value of amount
	 *
	 * @param mixed $amount
	 * @return self
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
		return $this;
	}
	
	/**
	 * Gets the value of buyDate

	 * @return mixed
	 */
	public function getBuyDate() {
		return $this->buyDate;
	}
	
	/**
	 * Sets the value of buyDate
	 *
	 * @param mixed $buyDate
	 * @return self
	 */
	public function setBuyDate($buyDate) {
		$this->buyDate = $buyDate;
		return $this;
	}
	
	/**
	 * Gets the value of buyer

	 * @return mixed
	 */
	public function getBuyer() {
		return $this->buyer;
	}
	
	/**
	 * Sets the value of buyer
	 *
	 * @param mixed $buyer
	 * @return self
	 */
	public function setBuyer($buyer) {
		$this->buyer = $buyer;
		return $this;
	}
	
	/**
	 * Gets the value of event

	 * @return mixed
	 */
	public function getEvent() {
		return $this->event;
	}
	
	/**
	 * Sets the value of event
	 *
	 * @param mixed $event
	 * @return self
	 */
	public function setEvent($event) {
		$this->event = $event;
		return $this;
	}
	}
