<?php

namespace CWS\Encute;

use CWS\Encute\Contracts\Enqueueable;

class Style extends Enqueue implements Contracts\EnqueueableStyle {
	/**
	 * @return $this
	 */
	public function footer(): \CWS\Encute\Contracts\Enqueueable {
		$this->dispatch(Actions\DeferredAction::class, function () {
			$allRelatedNodes = new self(DependencyGrapher::forStyles()->allRelatedNodes($this->getHandles()));
			$allRelatedNodes->dispatch(Actions\MoveStyleToFooter::class);
		});

		return $this;
	}

	/**
	 * @return $this
	 */
	public function remove(): \CWS\Encute\Contracts\Enqueueable {
		return $this->dispatch(Actions\RemoveStyle::class);
	}

	/**
	 * @return $this
	 */
	public function defer(): \CWS\Encute\Contracts\Enqueueable {
		return $this->dispatch(Actions\MakeStyleDefer::class);
	}

	public function keepIf(callable $condition): Enqueueable {
		$reverseCondition = function () use ($condition) {
			return !call_user_func($condition);
		};

		return $this->dispatch(Actions\RemoveStyleIf::class, $reverseCondition);
	}

	public function removeIf(callable $condition): Enqueueable {
		return $this->dispatch(Actions\RemoveStyleIf::class, $condition);
	}
}
