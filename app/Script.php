<?php

namespace CWS\Encute;

use CWS\Encute\Contracts\Enqueueable;

class Script extends Enqueue implements Contracts\EnqueueableScript {
	/**
	 * @return $this
	 */
	public function module(): \CWS\Encute\Contracts\EnqueueableScript {
		return $this->dispatch(Actions\MakeScriptModule::class);
	}

	/**
	 * @return $this
	 */
	public function noModule(): \CWS\Encute\Contracts\EnqueueableScript {
		return $this->dispatch(Actions\MakeScriptNoModule::class);
	}

	/**
	 * @return $this
	 */
	public function async(): \CWS\Encute\Contracts\EnqueueableScript {
		return $this->dispatch(Actions\MakeScriptAsync::class);
	}

	/**
	 * @return $this
	 */
	public function defer(): \CWS\Encute\Contracts\Enqueueable {
		return $this->dispatch(Actions\MakeScriptDefer::class);
	}

	/**
	 * @return $this
	 */
	public function footer(): \CWS\Encute\Contracts\Enqueueable {
		$this->dispatch(Actions\DeferredAction::class, function () {
			$allRelatedNodes = new self(DependencyGrapher::forScripts()->allRelatedNodes($this->getHandles()));
			$allRelatedNodes->dispatch(Actions\MoveScriptToFooter::class);
		});

		return $this;
	}

	/**
	 * @return $this
	 */
	public function remove(): \CWS\Encute\Contracts\Enqueueable {
		return $this->dispatch(Actions\RemoveScript::class);
	}

	public function keepIf(callable $condition): Enqueueable {
		$reverseCondition = function () use ($condition) {
			return !call_user_func($condition);
		};

		return $this->dispatch(Actions\RemoveScriptIf::class, $reverseCondition);
	}

	public function removeIf(callable $condition): Enqueueable {
		return $this->dispatch(Actions\RemoveScriptIf::class, $condition);
	}
}
