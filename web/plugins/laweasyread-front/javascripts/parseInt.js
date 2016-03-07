if(parseInt("一千一百十一")!=1111) { ///< 如果還不支援，才需要宣告
    parseInt = function() {
        var orig = parseInt;
        /**
         *  命名規則：
         *  * one:  數字「一」
         *  * ones: 個位數
         *  * tens: 十進制
         *  * wans: 萬進制
         *  * re:   正規表示式
         */

        var onesH = [
            "０零○〇", "１一壹ㄧ",
            "２二貳兩", "３三參", "４四肆", "５五伍",
            "６六陸", "７七柒", "８八捌", "９九玖"
        ]; /// "兩"字當量詞而被意外傳入時，後面會處理
        var ones = onesH.join("");
        var tens = "十百千拾佰仟";
        var wans = "萬億兆京垓秭穰溝澗正載極"; ///< 參閱WikiPedia條目〈中文數字〉

        var reOnesH = [];
        for(var i = 0; i < 10; ++i) reOnesH[i] = new RegExp("["+onesH[i]+"]", "g");
        var reOnes = new RegExp("[" + ones + "]", "g");
        var reTens = new RegExp("[" + tens + "]", "g");
        var reWans = new RegExp("([^負" + wans + "]+)([" + wans + "])", "g");
        var rePreOne = new RegExp("^([" + tens + wans + "])", "g"); ///< 句首補「一」
        var reInnerOne = new RegExp("([負零" + tens + wans + "])([" + tens + "])", "g"); ///< 處理如「二萬零十五」
        var reAllOnes = new RegExp("^負?["+ones+"]+$", "g");
        var reNonSupported = new RegExp("[^負"+ones+tens+wans+"]", "g");

        return function() {
            var result = orig.apply(this, arguments);
            if(!isNaN(result)) return result; ///< 如果是舊函數已支援的情形，那就回傳就函數的結果
			if(!arguments[0]) return;
            var str = arguments[0].replace(reNonSupported, ""); /// 移除不支援的字（包含 \d 喔）
            str = str.replace(/兩$/, ""); /// 移除末尾的「兩」字
            reAllOnes.lastIndex = 0;
            if(reAllOnes.test(str)) { /// 若均是零到九，直接一對一轉換
                str = str.replace(/^負/, "-");
                for(var i = 0; i < 10; ++i) {
                    reOnesH[i].lastIndex = 0;
                    str = str.replace(reOnesH[i], String(i));
                }
                return orig(str, 10);
            }
            /// 若有零到九以外的文字，才處理這裡
            reOnes.lastIndex = reTens.lastIndex = reWans.lastIndex = 0;
            rePreOne.lastIndex = reInnerOne.lastIndex = 0;
            str = str.replace(rePreOne, "一$1").replace(reInnerOne, "$1一$2");
            str = str.replace(reWans, function(match, belowWan, wan) {
                return "+(" + belowWan + ")*Math.pow(10000," + (wans.indexOf(wan)+1) + ")"
            }).replace(reTens, function(match) {
                return "*" + [10, 100, 1000][tens.indexOf(match)%3];
            }).replace(reOnes, function(match) {
                for(var i = 0; i < 10; ++i)
                    if(onesH[i].indexOf(match) >= 0)
                        return "+" + i;
            });
            str = str.replace(/^負(.*)/, "-($1)");
            try { return eval(str); }
            catch(e) {return NaN;}
        };
    }();
}