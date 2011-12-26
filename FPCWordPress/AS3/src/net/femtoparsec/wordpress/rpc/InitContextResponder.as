/*
    Copyright (c) 2011, Bastien Aracil
    All rights reserved.
    New BSD license. See http://en.wikipedia.org/wiki/Bsd_license

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
       * Redistributions of source code must retain the above copyright
         notice, this list of conditions and the following disclaimer.
       * Redistributions in binary form must reproduce the above copyright
         notice, this list of conditions and the following disclaimer in the
         documentation and/or other materials provided with the distribution.
       * The name of Bastien Aracil may not be used to endorse or promote products
         derived from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
    ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL BASTIEN ARACIL BE LIABLE FOR ANY
    DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
    ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
 * User: Bastien Aracil
 * Date: 24/12/11
 * Time: 15:20
 */
package net.femtoparsec.wordpress.rpc {
import mx.rpc.IResponder;

import net.femtoparsec.wordpress.IWordPress;
import net.femtoparsec.wordpress.IWordPressHolder;
import net.femtoparsec.wordpress.rpc.RPCUtils;

public class InitContextResponder implements IResponder {

    private var _context:IWordPress;

    public function InitContextResponder(context:IWordPress) {
        _context = context;
    }

    public function result(value:Object):void {
        var result:* = RPCUtils.getResult(value);
        this.initContext(result);
    }

    public function fault(value:Object):void {
        var rootCause:* = RPCUtils.getRootCause(value);
        this.initContext(rootCause);
    }

    private function initContext(value:*):void {
        var oneValue:*;
        if (value is IWordPressHolder) {
            (value as IWordPressHolder).context = _context;
        }
        else if (value is Array) {
            for each (oneValue in value) {
                this.initContext(oneValue);
            }
        }
        else if (value is Object) {
            for each (oneValue in value) {
                this.initContext(oneValue);
            }
        }
    }

}
}
