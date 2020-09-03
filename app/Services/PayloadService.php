<?php
namespace Metos\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Metos\Helpers\DataParser;

class PayloadService
{
    public static function getPayload() :string
    {
        $data = [
            "ddtsgAFwAG8AAJ0DQAPdCdcImgfmDMIMBA2lAaEBrAHdA8cD7QM2BzAHIgEZAQA=",
            "7qfngAFwAG8AAJ0DQAPaCeYIDgeOCIUImwj0Au4C/wInARsBLwHOBrcGPwA9AA8=",
            "iT/pgAFwAG8AAJ0DQAPZCfEIDgeWCI0IoAjYAs8C4AJLAUIBWAGYBo4GRgBFAA8=",
            "7LLqgAFwAG8AAJ0DQAPaCeIIDgetCKgItwjAArwCxQJrAWYBcAF6BmwGTgBNAA8=",
            "corrgAFwAG8AAJ0DQAPbCbQIDge9CLEIyQi+ArICxgJuAWUBgAGGBnQGTwBNAAo=",
            "B2rsgAFwAG8AAJ0DQAPbCagIDgeZCIoIrwi5ArUCvgJyAWsBegFXBlIGTwBOAAA=",
            "NIXvgAFwAG8AAJ0DQAPZCe4FDgeFB18HogcVAwkDIQPvAOAA/wAOBgAGLgArAAA=",
            "9nnwgAFwAG8AAJ0DQAPVCcQFDgdLBzUHZgckAwkDSgPaAK8A/ADzBdgFKQAhAAA=",
            "73DxgAFwAG8AAJ0DQAPZCc4FEAcjBxgHLQdvA2kDdwODAHwAiwBZBksGGAAXAAo=",
            "lSz0gAFwAG8AAJ0DQAPYCakIHAfLBsUG1wa0A54DzAMxABYATAB4Bl4GCQAFAA8=",
            "YOf1gAFwAG8AAJ0DQAPZCd8IHAfJBsQG0QbkA98D6AP8//f/AQDEBsIGAAAAAA8=",
            "95b4gAFwAG8AAJ0DQAPVCYoFHAftBtoGAgfKA74D1gMZAAwAKAC+Br4GBQADAA8=",
            "qHUBgAFwAG8AAJ0DQAPKCYcFPAcpBicGKwboA+gD6APz//P/8/8oBicGAAAAAA8=",
            "b4kEgAFwAG8AAJ0DQAO/CeUEPAcqBhwGOQbhA9kD6AP6//P/AwAgBhYGAAAAAA8=",
            "Q1kGgAFwAG8AAJ0DQAO3CcoEPAcbBhIGLQboA+gD6APz//L/8/8aBhEGAAAAAA8=",
            "I4IIgAFwAG8AAJ0DQAOtCcACPAcqBiIGNwboA+gD6APz//L/8/8qBiEGAAAAAA8=",
            "Qv8NgAFwAG8AAJ0DQAOXCQAAPAcaBhoGGgboA+gD6APy//L/8v8aBhoGAAAAAA8=",
            "gz8QgAFwAG8AAJ0DQAOFCQAAPAcjBhIGNgbTA7oD6AMJAPL/JQACBsgFAwAAAA8=",
            "3oEWgAFwAG8AAJ0DQANmCQAARgfcBdAF7QXoA+gD6APx//D/8f/cBdAFAAAAAA8=",
            "RXIXgAFwAG8AAJ0DQANkCQAARgfDBcEFyAXoA+gD6APw//D/8P/DBcEFAAAAAA8=",
            "2IcYgAFwAG8AAJ0DQANdCQAASAfSBcsF2gXoA+gD6APw//D/8P/RBcsFAAAAAA8=",
            "nQkZgAFwAG8AAJ0DQANaCQAASAfBBb8FxAXoA+gD6APw/+//8P/BBb8FAAAAAA8=",
            "uKAagAFwAG8AAJ0DQANSCQAASgfFBbcF2AXoA+gD6APw/+//8P/FBbcFAAAAAA8=",
            "v44bgAFwAG8AAJ0DQANQCQAASge2BbQFugXoA+gD6APv/+//7/+2BbQFAAAAAA8=",
            "rcEcgAFwAG8AAJ0DQANHCQAASge8Ba4FzwXoA+gD6APw/+//8P+7Ba0FAAAAAA8=",
            "H6MdgAFwAG8AAJ0DQANFCQAASgeiBZ8FqAXoA+gD6APv/+7/7/+hBZ8FAAAAAA8=",
            "JW4fgAFwAG8AAJ0DQAM6CQAASgeWBZQFmQXoA+gD6APu/+7/7v+WBZQFAAAAAA8=",
            "TashgAFwAG8AAJ0DQAMvCQAASgeCBYIFhQXoA+gD6APu/+7/7v+CBYIFAAAAAA8=",
            "DK8lgAFwAG8AAJ0DQAMZCQAASgeHBYYFigXoA+gD6APu/+7/7v+HBYYFAAAAAA8=",
            "Ly8ogAFwAG8AAJ0DQAMECQAASgeaBZQFpQXoA+gD6APv/+7/7/+aBZQFAAAAAA8=",
            "RUApgAFwAG8AAJ0DQAMCCQAASgeSBZEFlAXoA+gD6APu/+7/7v+SBZEFAAAAAA8=",
            "pSMqgAFwAG8AAJ0DQAP5CAAASgeaBZQFpgXoA+gD6APv/+7/7/+aBZQFAAAAAA8=",
            "PxArgAFwAG8AAJ0DQAP3CHUASgeZBZcFmwXoA+gD6APu/+7/7v+ZBZcFAAAAAA8=",
            "TrUsgAFwAG8AAJ0DQAPuCHcBUAekBZYFtAXoA+gD6APv/+7/7/+kBZYFAAAAAA8=",
            "FhMtgAFwAG8AAJ0DQAPrCHYDWAePBYsFlAXoA+gD6APu/+7/7v+PBYsFAAAAAA8=",
            "/GAvgAFwAG8AAJ0DQAPfCNADbAd0BXMFdgXoA+gD6APt/+3/7f90BXIFAAAAAA8=",
            "BAEwgAFwAG8AAJ0DQAPWCJ0EfAd2BWoFigXoA+gD6APu/+3/7v92BWoFAAAAAA8=",
            "H7AygAFwAG8AAJ0DQAPOCKIFggdrBWIFdwXoA+gD6APt/+3/7f9rBWIFAAAAAA8=",
            "vf0zgAFwAG8AAJ0DQAPOCI8FggdpBWYFawXoA+gD6APt/+3/7f9oBWYFAAAAAA8=",
            "UP83gAFwAG8AAJ0DQAPGCPgFhAeKBYcFjgXoA+gD6APu/+7/7v+KBYYFAAAAAA8=",
            "m1k4gAFwAG8AAJ0DQAPDCCQGhAeRBYsFmwXoA+gD6APu/+7/7v+RBYsFAAAAAA8=",
            "COs+gAFwAG8AAJ0DQAPgCKoGhAfTBc4F3QXoA+gD6APw//D/8P/TBc4FAAAAAA8=",
            "YhE/gAFwAG8AAJ0DQAMACZgGhAcDBvoFCQbmA+ID6AP0//H/+P8ABvkFAAAAAA8=",
            "0pxCgAFwAG8AAJ0DQANZCasGhAeQBocGpAaXA40DngNPAEkAXQAOBg4GDwAOAA8=",
            "zOhDgAFwAG8AAJ0DQAObCZoGhAf9BtAGKQdxA1gDjQN/AF8AngA2BjQGGAASAA8=",
            "6stEgAFwAG8AAJ0DQAPYCfQIhAdeB0YHbAcoAx4DMwPWAMkA4wAQBgYGKQAmAA8=",
            "OnFGgAFwAG8AAJ0DQAPZCfYIhAcZBwsHLwc9Ay4DRgO7ALIAzgDyBesFIwAhAA8=",
            "fAhHgAFwAG8AAJ0DQAPZCQcJhAc3BxcHVgclAxkDLwPXAMwA6ADjBdUFKAAmAAo=",
            "kOpJgAFwAG8AAJ0DQAPYCT8JhAfpB88HDwjXArgC8wI+ARwBZwHwBdEFQAA5AAA=",
            "KHFNgAFwAG8AAJ0DQAPXCQoJhAc1CQwJSwlmAmACbALwAesB+wElBv4FbwBtAAA=",
            "fPxPgAFwAG8AAJ0DQAPYCfEIhAeXCG0IvgiCAn4CiQK3AasBwgHWBb4FXQBaAAA=",
            "esZTgAFwAG8AAJ0DQAPZCSsGhgc5B+sGjQfzArECLwMSAckAZQF/BUQFMwAlAAU=",
            "Ix5cgAFwAG8AAJ0DQAPYCeQImAfdBtQG6wamA6IDqQNCAD8ASABzBm4GDAAMAA8=",
            "iZBdgAFwAG8AAJ0DQAPZCaEImAf8BvoG/waEA3oDjwNqAF8AdQBVBkMGFAASAA8=",
            "LyhegAFwAG8AAJ0DQAPZCWYImAcOBwwHEQdiA1sDaQOSAIoAmgArBiEGGwAaAA8=",
            "0ThggAFwAG8AAJ0DQAPYCUIImAcyBygHOwcPAwgDHQPwAOEA+gCyBakFLQAqAA8=",
            "RCZhgAFwAG8AAJ0DQAPYCS8ImAdJB0MHTwcSAwsDHAPuAOQA9wDRBbsFLQArAA8=",
            "JY5igAFwAG8AAJ0DQAPZCRsImAdBBzUHTQcRAwsDGgPvAOUA9wDFBbkFLQArAA8=",
            "D7xkgAFwAG8AAJ0DQAPVCcUFmAcIB/MGIwcJA/gCEwP1AOkACgF+BXYFLQArAA8=",
            "SChlgAFwAG8AAJ0DQAPWCaAFmAfOBsQG3QYnAxkDOAPPAL0A4QCBBXMFJQAiAA8=",
            "oKBogAFwAG8AAJ0DQAPICf4DmAdnBk8GggaAA2MDmANoAE4AigC9BaQFEgAOAA8=",
            "Hg9rgAFwAG8AAJ0DQAO9CQAAmAfrBeYF8QXRA88D1QMJAAUADADIBcYFAwADAA8=",
            "zvZsgAFwAG8AAJ0DQAO0CQAAmAffBc8F+AXRA74D4QMIAPj/HwC8BbYFAwABAA8=",
            "MfpugAFwAG8AAJ0DQAOpCQAAmAeaBYgFsQXiA9YD6AP1/+7/AgCRBYgFAAAAAA8=",
            "ntpvgAFwAG8AAJ0DQAOmCQAAmAeBBX8FhgXoA+gD6APu/+3/7v+BBX8FAAAAAA8=",
            "RIhwgAFwAG8AAJ0DQAOdCQAAmAeFBXUFmAXmA+UD6APv/+3/8f+DBXUFAAAAAA8=",
            "HkJygAFwAG8AAJ0DQAOTCQAAmAdoBWEFcwXoA+gD6APt/+3/7f9nBWEFAAAAAA8=",
            "HNZzgAFwAG8AAJ0DQAORCQAAmAdQBUoFWQXoA+gD6APs/+z/7P9QBUoFAAAAAA8=",
            "7OV0gAFwAG8AAJ0DQAOJCQAAmAdIBTgFWgXoA+gD6APs/+v/7P9IBTgFAAAAAA8=",
            "Lnp2gAFwAG8AAJ0DQAN+CQAAmAcoBSAFOAXoA+gD6APr/+v/6/8oBSAFAAAAAA8=",
            "LmV5gAFwAG8AAJ0DQANwCQAAmAfuBOoE8gToA+gD6APp/+n/6f/uBOoEAAAAAA8=",
            "mKt6gAFwAG8AAJ0DQANoCQAAmAfyBOMEBgXoA+gD6APq/+n/6v/yBOMEAAAAAA8=",
            "Yex8gAFwAG8AAJ0DQANdCQAAmAfNBMQE3AToA+gD6APo/+j/6P/NBMMEAAAAAA8=",
            "93F9gAFwAG8AAJ0DQANaCQAAmAe/BL0ExAToA+gD6APo/+j/6P++BLwEAAAAAA8=",
            "wLF+gAFwAG8AAJ0DQANTCQAAmAe9BLAEzgToA+gD6APo/+f/6P+9BK8EAAAAAA8=",
            "1Hx/gAFwAG8AAJ0DQANRCQAAmAeiBJoEqgToA+gD6APn/+b/5/+iBJoEAAAAAA8=",
            "qeWAgAFwAG8AAJ0DQANICQAAmAegBJcErwToA+gD6APn/+b/5/+gBJYEAAAAAA8=",
            "xJiBgAFwAG8AAJ0DQANGCQAAmAeIBIQEjgToA+gD6APm/+b/5v+IBIQEAAAAAA8=",
            "IQGCgAFwAG8AAJ0DQAM+CQAAmAeOBIQEnQToA+gD6APn/+b/5/+OBIQEAAAAAA8=",
            "0KGEgAFwAG8AAJ0DQAMzCQAAmAd8BG8EjwToA+gD6APm/+X/5v98BG8EAAAAAA8=",
            "peGFgAFwAG8AAJ0DQAMyCQAAmAdkBF0EagToA+gD6APl/+X/5f9kBFwEAAAAAA8=",
            "S8eIgAFwAG8AAJ0DQAMdCQAAmAdTBEYEYgToA+gD6APl/+T/5f9TBEYEAAAAAA8=",
            "FB+JgAFwAG8AAJ0DQAMaCQAAmAdBBDwERgToA+gD6APk/+T/5P9BBDwEAAAAAA8=",
            "yWeKgAFwAG8AAJ0DQAMRCVkAmAc4BCoETAToA+gD6APk/+P/5P83BCkEAAAAAA8=",
            "GDeMgAFwAG8AAJ0DQAMGCbUDmAcxBCoEQQToA+gD6APk/+P/5P8xBCkEAAAAAA8=",
            "U5+NgAFwAG8AAJ0DQAMECa8EmAcmBCAEKgToA+gD6APj/+P/4/8lBCAEAAAAAA8=",
            "2D6SgAFwAG8AAJ0DQAPtCNQFmAcxBC0EOQToA+gD6APk/+P/5P8wBCwEAAAAAA8=",
            "EemTgAFwAG8AAJ0DQAPvCNkFmAc+BDcERgToA+gD6APk/+P/5P8+BDcEAAAAAA8=",
            "f+OVgAFwAG8AAJ0DQAPwCA8GmgeXBIAErgToA+gD6APn/+b/5/+XBIAEAAAAAA8=",
            "j3WYgAFwAG8AAJ0DQAPyCBgGmgfxBd4FBwafA5kDrQNBADQASQB8BV4FDAAKAA8=",
            "9FOZgAFwAG8AAJ0DQAP3CA0Gmgc1BhwGTgZ9A2YDjgNpAFYAhQCFBXUFEgAPAA8=",
            "HZycgAFwAG8AAJ0DQAMICUYGmgf/BugGFwcCA+4CDgP8AO4AFQFmBVYFLgArAA8=",
            "jSGegAFwAG8AAJ0DQAMjCVkGmgeiB3gHxgfJAsQC1AJJATkBUwGPBX0FQAA8AA8=",
            "ibqggAFwAG8AAJ0DQANWCZ0GmgdYCEAIeQhzAmACiALFAakB4gF0BWMFXgBYAAA=",
            "0AOhgAFwAG8AAJ0DQAOFCdkGmgeYCIwIqghSAlACVAL4AfQB/gFdBVgFagBpAAA=",
            "pkKigAFwAG8AAJ0DQAO7CZIGmgedCIYItQhSAk4CVgL4AfMB/AFjBUMFawBqAAA=",
            "1LGrgAFwAG8AAJ0DQAPZCRcJmgddCk4KdQr0AfMB9gHHAsICzAL4Be0FrACqAAA=",
            "zxisgAFwAG8AAJ0DQAPZCR8JmgeFCoAKkArvAe8B8AHXAtUC2QIMBgYGsgCyAAA=",
            "3OOtgAFwAG8AAJ0DQAPYCR8JmgdiCkYKjwrqAegB7gHXAs4C3wLhBcoFsACtAAA=",
            "8daugAFwAG8AAJ0DQAPZCScJmgeICnEKpgroAeIB8QHiAtAC7AL7BeIFtQCwAAA=",
            "mCWygAFwAG8AAJ0DQAPZCRQJmgccCwgLRQvWAdYB1gEeAxoDJgNGBjQGzQDKAAA=",
            "hSOzgAFwAG8AAJ0DQAPbCRQJmgfkCtIK+Aq3AakB0AFKAyIDYQOoBWUF0QDLAAA=",
            "4iy1gAFwAG8AAJ0DQAPaCe4ImgccC/EKRAuoAaQBrAFyA2MDhAOlBYwF3gDYAAA=",
            "xca3gAFwAG8AAJ0DQAPbCecImgfgCsoKAgu4Aa8BwAFIAzYDYAOpBacF0QDMAAA=",
            "Q924gAFwAG8AAJ0DQAPdCZkImgfECrsK0gq7AbQBwQE9AzMDTAOaBY8FzADKAAA=",
            "+DC5gAFwAG8AAJ0DQAPdCe8FmgfYCtIK3wq6AbUBwgFBAzUDTQOsBZ4FzwDNAAA=",
            "Ao69gAFwAG8AAJ0DQAPcCb8FmgdsCl4KdQrMAcsB0AEMAwkDEQOGBXUFuwC7AAA=",
            "7TXAgAFwAG8AAJ0DQAPcCdIHmgdYClYKXArWAdQB2QH3AvUC+wKWBYwFtgC2AAA=",
            "lXnDgAFwAG8AAJ0DQAPbCZYFmgfPCbkJ5An9AfkBAwKgApQCqwKQBY4FnACYAAA=",
            "/dDHgAFwAG8AAJ0DQAPXCZkEmgceCQ4JLQk3AjECPQIuAiQCOgKUBZAFewB5AAA=",
            "y9bIgAFwAG8AAJ0DQAPVCbADmgfpCNcI/AhUAkoCXQL/AfMBDwKvBaIFbwBtAAA=",
            "wsXJgAFwAG8AAJ0DQAPRCRcCmgeECGoIpgh6AmoChgLBAa8B2gGuBacFXgBaAAA=",
            "CwLKgAFwAG8AAJ0DQAPPCV4AmgdCCCsIWwiTAogCnAKZAYwBqwGuBaoFVABRAAA=",
            "KCTNgAFwAG8AAJ0DQAPHCQAAmge5B68HwwfQAskC1gJEATwBTQGzBa4FQAA+AAA=",
            "eKvOgAFwAG8AAJ0DQAPFCQAAmgedB5EHqAfgAt4C5gItAScBMgG7BbcFOwA6AAA=",
            "Gq/PgAFwAG8AAJ0DQAPCCQAAmgd7B3MHhQfxAusC+AIXAQ8BIQG9BbgFNgA0AAA=",
            "xhTQgAFwAG8AAJ0DQAPACQAAmgdXB04HYgcAA/0CBAMDAQABCQG5BbMFMQAxAAA=",
            "SvzRgAFwAG8AAJ0DQAO9CQAAmgc9BzIHSAcQAwoDFgPwAOoA+AC/Bb0FLQAsAAA=",
            "s7fVgAFwAG8AAJ0DQAOtCQAAmgfNBsMG3AY+AzsDQQO2ALMAuwCrBaYFIAAgAAA=",
            "cx7WgAFwAG8AAJ0DQAOlCQAAmge9BrMGzgZAAzIDSgO0AKgAxQCfBZQFIAAeAAA=",
            "L1nXgAFwAG8AAJ0DQAOiCQAAmgejBpsGrAZSA00DVgOdAJkApQCmBaUFHAAbAAA=",
        ];

        $random_index = rand(0, count($data));

        return $data[$random_index];
    }

    public static function PrettyPayload() 
    {
        $payload = self::getPayload();
        $header = DataParser::parseHeader(substr($payload, 0, 14));
        $data   = DataParser::parseData(substr($payload, 14));
        $email  = DataParser::getEmailbySerial($header);
        
        return [
            "original_payload" => $payload,
            "header" => $header,
            "data" => $data,
            "email" => $email 
        ];
    }

}